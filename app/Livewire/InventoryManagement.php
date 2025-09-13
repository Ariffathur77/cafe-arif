<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InventoryItem;
use App\Models\StockTransaction; // Import StockTransaction

class InventoryManagement extends Component
{
    public $item_id, $name, $unit, $current_stock, $low_stock_threshold;
    public $isModalOpen = false;

    // Properti untuk modal transaksi stok
    public $isStockModalOpen = false;
    public $selected_item_id, $selected_item_name;
    public $transaction_type = 'stock_in';
    public $transaction_quantity;
    public $transaction_notes;


    public function render()
    {
        $items = InventoryItem::latest()->get();
        return view('livewire.inventory-management', [
            'items' => $items
        ])->layout('layouts.app');
    }

    // Fungsi CRUD Item (tetap sama)
    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $this->item_id = $id;
        $this->name = $item->name;
        $this->unit = $item->unit;
        $this->current_stock = $item->current_stock;
        $this->low_stock_threshold = $item->low_stock_threshold;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isStockModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->item_id = null;
        $this->name = '';
        $this->unit = '';
        $this->current_stock = 0;
        $this->low_stock_threshold = 0;

        $this->selected_item_id = null;
        $this->selected_item_name = '';
        $this->transaction_type = 'stock_in';
        $this->transaction_quantity = null;
        $this->transaction_notes = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'low_stock_threshold' => 'required|numeric|min:0',
        ]);

        InventoryItem::updateOrCreate(['id' => $this->item_id], [
            'name' => $this->name,
            'unit' => $this->unit,
            'current_stock' => $this->current_stock,
            'low_stock_threshold' => $this->low_stock_threshold,
        ]);

        session()->flash('message', $this->item_id ? 'Item berhasil diperbarui.' : 'Item berhasil ditambahkan.');
        $this->closeModal();
    }

    public function delete($id)
    {
        InventoryItem::find($id)->delete();
        session()->flash('message', 'Item berhasil dihapus.');
    }

    // --- FUNGSI BARU UNTUK MANAJEMEN STOK ---

    // Membuka modal untuk transaksi stok
    public function openStockModal($itemId)
    {
        $this->resetForm();
        $item = InventoryItem::findOrFail($itemId);
        $this->selected_item_id = $item->id;
        $this->selected_item_name = $item->name;
        $this->isStockModalOpen = true;
    }

    // Menyimpan transaksi stok
    public function saveStockTransaction()
    {
        $this->validate([
            'transaction_quantity' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:stock_in,waste,adjustment',
            'transaction_notes' => 'nullable|string',
        ]);

        $item = InventoryItem::findOrFail($this->selected_item_id);
        $quantity = $this->transaction_quantity;

        if ($this->transaction_type == 'stock_in' || $this->transaction_type == 'adjustment') {
            // Jika stok masuk atau penyesuaian, tambahkan ke stok saat ini
            $item->increment('current_stock', $quantity);
        } else { // 'waste'
            // Jika barang rusak/buang, kurangi stok
            $item->decrement('current_stock', $quantity);
            $quantity = -$quantity; // Catat sebagai negatif di transaksi
        }

        // Catat di tabel riwayat transaksi stok
        StockTransaction::create([
            'inventory_item_id' => $this->selected_item_id,
            'type' => $this->transaction_type,
            'quantity' => $quantity,
            'notes' => $this->transaction_notes,
        ]);

        session()->flash('message', 'Transaksi stok berhasil dicatat.');
        $this->closeModal();
    }
}
