<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StockTransaction;
use App\Models\InventoryItem;

class StockHistory extends Component
{
    public $start_date;
    public $end_date;
    public $selected_item_id = 'all';

    public function render()
    {
        $query = StockTransaction::with('inventoryItem', 'order')
            ->when($this->start_date, function ($q) {
                $q->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($q) {
                $q->whereDate('created_at', '<=', $this->end_date);
            })
            ->when($this->selected_item_id !== 'all', function ($q) {
                $q->where('inventory_item_id', $this->selected_item_id);
            })
            ->latest();

        $transactions = $query->get();
        $items = InventoryItem::orderBy('name')->get();

        return view('livewire.stock-history', [
            'transactions' => $transactions,
            'items' => $items
        ])->layout('layouts.app');
    }
}
