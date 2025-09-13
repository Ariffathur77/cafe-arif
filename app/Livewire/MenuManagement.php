<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MenuManagement extends Component
{
    // Gunakan semua trait yang dibutuhkan
    use WithPagination;
    use WithFileUploads;

    // Properti untuk data form
    public $menu_id, $name, $description, $price, $category_id;
    public $image, $existingImageUrl; // Properti untuk gambar

    // Properti untuk kontrol UI
    public $isModalOpen = false;
    public $search = '';

    // Atur tema pagination agar sesuai dengan Tailwind CSS
    protected $paginationTheme = 'tailwind';

    // Validasi akan dijalankan secara real-time untuk input gambar
    public function updatedImage()
    {
        $this->validate(['image' => 'nullable|image|max:2048']);
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset ke halaman pertama setiap kali melakukan pencarian
    }

    public function render()
    {
        $menus = Menu::with('category')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($subQuery) {
                        $subQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10); // Menampilkan 10 item per halaman

        $categories = Category::all();

        return view('livewire.menu-management', [
            'menus' => $menus,
            'categories' => $categories,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);

        // --- START: Klarifikasi di sini ---
        // Isi semua properti form dengan data menu yang akan diedit
        $this->menu_id = $id;
        $this->name = $menu->name;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->category_id = $menu->category_id;

        // Simpan URL gambar yang ada untuk ditampilkan sebagai pratinjau "gambar saat ini"
        $this->existingImageUrl = $menu->image_url;

        // Penting: Reset properti $image ke null.
        // Ini memastikan input type="file" di modal kosong saat edit,
        // sehingga user bisa memilih file baru jika ingin mengganti gambar.
        $this->image = null;
        // --- END: Klarifikasi di sini ---

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->price = preg_replace('/[^0-9]/', '', $this->price);

        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Validasi gambar maks 2MB
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
        ];

        // Logika untuk menangani unggahan gambar
        if ($this->image) {
            // Hapus gambar lama jika ada saat update
            if ($this->menu_id && $this->existingImageUrl) {
                Storage::disk('public')->delete($this->existingImageUrl);
            }
            // Simpan gambar baru dan dapatkan path-nya
            $data['image_url'] = $this->image->store('menus', 'public');
        }

        Menu::updateOrCreate(['id' => $this->menu_id], $data);

        session()->flash('message', $this->menu_id ? 'Menu berhasil diperbarui.' : 'Menu berhasil ditambahkan.');

        $this->closeModal();
    }

    public function delete($id)
    {
        $menu = Menu::find($id);

        // Hapus gambar dari storage jika ada
        if ($menu && $menu->image_url) {
            Storage::disk('public')->delete($menu->image_url);
        }

        $menu->delete();
        session()->flash('message', 'Menu berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->menu_id = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->category_id = '';
        $this->image = null;             // Reset input file gambar
        $this->existingImageUrl = null;  // Reset pratinjau gambar lama
    }
}
