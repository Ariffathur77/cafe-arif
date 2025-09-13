<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryManagement extends Component
{

    // Properti untuk data form
    public $category_id, $name, $description;

    // Properti untuk kontrol modal
    public $isModalOpen = false;

    public function render()
    {
        $categories = Category::latest()->get();
        return view('livewire.category-management', [
            'categories' => $categories,
        ])->layout('layouts.app');
    }

    // Membuka modal untuk membuat data baru
    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    // Membuka modal untuk mengedit data
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->isModalOpen = true;
    }

    // Menutup modal
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    // Mereset form input
    private function resetForm()
    {
        $this->category_id = null;
        $this->name = '';
        $this->description = '';
    }

    // Menyimpan atau mengupdate data
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::updateOrCreate(['id' => $this->category_id], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message',
            $this->category_id ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');

        $this->closeModal();
    }

    // Menghapus data
    public function delete($id)
    {
        Category::find($id)->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }
}
