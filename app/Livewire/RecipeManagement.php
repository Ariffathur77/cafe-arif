<?php

namespace App\Livewire;

use App\Models\InventoryItem;
use App\Models\Menu;
use App\Models\MenuRecipe;
use Livewire\Component;

class RecipeManagement extends Component
{
    public Menu $menu; // Ini akan otomatis diisi oleh Livewire dari parameter route
    public $inventory_items;

    // Properti untuk form tambah bahan
    public $inventory_item_id;
    public $quantity_used;

    // Aturan validasi
    protected $rules = [
        'inventory_item_id' => 'required|exists:inventory_items,id',
        'quantity_used' => 'required|numeric|min:0.01',
    ];

    // Fungsi mount() akan dijalankan saat komponen pertama kali dibuat
    public function mount(Menu $menu)
    {
        $this->menu = $menu;
        $this->inventory_items = InventoryItem::orderBy('name')->get();
    }

    public function render()
    {
        // Muat ulang resep setiap kali render untuk data terbaru
        $recipes = $this->menu->recipes()->with('inventoryItem')->get();

        return view('livewire.recipe-management', [
            'recipes' => $recipes
        ])->layout('layouts.app');
    }

    // Fungsi untuk menambahkan bahan baru ke resep
    public function addIngredient()
    {
        $this->validate();

        $this->menu->recipes()->create([
            'inventory_item_id' => $this->inventory_item_id,
            'quantity_used' => $this->quantity_used,
        ]);

        session()->flash('message', 'Bahan berhasil ditambahkan ke resep.');
        $this->reset(['inventory_item_id', 'quantity_used']); // Reset form
    }

    // Fungsi untuk menghapus bahan dari resep
    public function removeIngredient($recipeId)
    {
        MenuRecipe::find($recipeId)->delete();
        session()->flash('message', 'Bahan berhasil dihapus dari resep.');
    }
}
