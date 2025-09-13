<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Table;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use App\Services\OrderService;

class MenuDisplay extends Component
{
    public Table $table;
    public $cart = [];
    public $customer_name = '';
    public $isCheckoutModalOpen = false;
    protected $listeners = ['openCheckoutConfirmation'];

    public function mount(Table $table)
    {
        $this->table = $table;
        $this->cart = session()->get('cart', []);
    }

    public function render()
    {
        $categories = Category::whereHas('menus', fn($q) => $q->where('is_available', true))
            ->with(['menus' => fn($q) => $q->where('is_available', true)])
            ->get();

        $activePromos = Promo::with('menus')->where('is_active', true)->get()->filter->is_currently_active;

        $categories->each(function ($category) use ($activePromos) {
            $category->menus->each(function ($menu) use ($activePromos) {
                $menu->original_price = $menu->price;
                $menu->final_price = $menu->price;

                $applicablePromo = $activePromos->first(function ($promo) use ($menu) {
                    return $promo->menus->isEmpty() || $promo->menus->contains($menu->id);
                });

                if ($applicablePromo) {
                    if ($applicablePromo->type == 'percentage') {
                        $discount = ($menu->price * $applicablePromo->value) / 100;
                        $menu->final_price = $menu->price - $discount;
                    } else {
                        $menu->final_price = $menu->price - $applicablePromo->value;
                    }
                }
            });
        });

        session()->put('cart', $this->cart);

        return view('livewire.menu-display', [
            'categories' => $categories,
        ])->layout('layouts.guest');
    }

    public function addToCart($menuId, $finalPrice)
    {
        $menu = Menu::find($menuId);
        if (!$menu) return;

        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity']++;
        } else {
            $this->cart[$menuId] = [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'price'    => $finalPrice,
                'quantity' => 1,
            ];
        }
        session()->flash('message', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart($menuId)
    {
        unset($this->cart[$menuId]);
        session()->flash('message', 'Menu berhasil dihapus dari keranjang.');
    }

    public function increaseQuantity($menuId)
    {
        $this->cart[$menuId]['quantity']++;
    }

    public function decreaseQuantity($menuId)
    {
        if ($this->cart[$menuId]['quantity'] > 1) {
            $this->cart[$menuId]['quantity']--;
        } else {
            $this->removeFromCart($menuId);
        }
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function openCheckoutConfirmation()
    {
        // Validasi nama sebelum membuka modal
        $this->validate(['customer_name' => 'required|string|max:255']);

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang Anda kosong!');
            return;
        }

        $this->isCheckoutModalOpen = true;
    }

    public function checkout(OrderService $orderService)
    {
        $this->validate(['customer_name' => 'required|string|max:255']);

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang Anda kosong!');
            return;
        }

        try {
            $order = $orderService->createOrder(
                $this->table,
                $this->cart,
                $this->customer_name
            );

            session()->forget('cart');

            return redirect()->route('order.success', $order->order_code);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }
}
