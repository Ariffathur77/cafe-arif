<?php

namespace App\Livewire;

use App\Models\Table;
use Livewire\Component;

class TableDashboard extends Component
{
    // Fungsi untuk mengubah status meja
    public function updateStatus($tableId, $status)
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->status = $status;
            $table->save();
        }
    }

    public function render()
    {
        $tables = Table::orderBy('table_number', 'asc')->get();
        return view('livewire.table-dashboard', [
            'tables' => $tables
        ])->layout('layouts.app');
    }
}
