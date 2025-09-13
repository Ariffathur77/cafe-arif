<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Category;

class MenuManagement extends Component
{
    // Properti untuk data form
    public $menu_id, $name, $description, $price, $category_id;

    // Properti untuk kontrol modal
    public $isModalOpen = false;

    public function render()
    {
        $menus = Menu::with('category')->latest()->get();
        $categories = Category::all();

        return view('livewire.menu-management', [
            'menus' => $menus,
            'categories' => $categories,
        ])->layout('layouts.app');
    }

    // Fungsi untuk membuka modal (mode tambah baru)
    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    // Fungsi untuk membuka modal (mode edit)
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->menu_id = $id;
        $this->name = $menu->name;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->category_id = $menu->category_id;

        $this->isModalOpen = true;
    }

    // Fungsi untuk menutup modal
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    // Fungsi untuk mereset form
    private function resetForm()
    {
        $this->menu_id = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->category_id = '';
    }

    // Fungsi untuk menyimpan (bisa untuk data baru atau update)
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        Menu::updateOrCreate(['id' => $this->menu_id], [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
        ]);

        session()->flash('message',
            $this->menu_id ? 'Menu berhasil diperbarui.' : 'Menu berhasil ditambahkan.');

        $this->closeModal();
    }

    // Fungsi untuk menghapus menu
    public function delete($id)
    {
        Menu::find($id)->delete();
        session()->flash('message', 'Menu berhasil dihapus.');
    }
}
