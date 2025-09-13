<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Table;
use App\Models\Menu;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    // Sederhanakan signature fungsi ini
    public function createOrder(Table $table, array $cart, string $customerName): Order
    {
        return DB::transaction(function () use ($table, $cart, $customerName) {

            // Hitung total dari data keranjang yang sudah final
            $finalAmount = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // (Opsional) Hitung total harga asli untuk data diskon
            $totalAmount = 0;
            $table->status = 'occupied';
            $table->save();
            foreach($cart as $item) {
                $menu = Menu::find($item['id']);
                $totalAmount += $menu->price * $item['quantity'];
            }
            $discountAmount = $totalAmount - $finalAmount;

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'table_id' => $table->id,
                'customer_name' => $customerName,
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $order->orderDetails()->create([
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price_at_order' => $item['price'], // Harga yang disimpan adalah harga final
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $menu = Menu::with('recipes.inventoryItem')->find($item['id']);
                foreach ($menu->recipes as $recipe) {
                    $inventoryItem = $recipe->inventoryItem;
                    $quantityToReduce = $recipe->quantity_used * $item['quantity'];

                    $inventoryItem->decrement('current_stock', $quantityToReduce);

                    StockTransaction::create([
                        'inventory_item_id' => $inventoryItem->id,
                        'order_id' => $order->id,
                        'type' => 'sale',
                        'quantity' => -$quantityToReduce,
                        'notes' => 'Penjualan menu ' . $menu->name,
                    ]);
                }
            }

            return $order;
        });
    }
}
