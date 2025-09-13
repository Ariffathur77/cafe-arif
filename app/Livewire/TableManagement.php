<?php

namespace App\Livewire;

use App\Models\Table;
use Livewire\Component;
use Illuminate\Support\Str;

class TableManagement extends Component
{
    public $table_id, $table_number;
    public $isModalOpen = false;

    public function render()
    {
        $tables = Table::latest()->get();
        return view('livewire.table-management', [
            'tables' => $tables,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $table = Table::findOrFail($id);
        $this->table_id = $id;
        $this->table_number = $table->table_number;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->table_id = null;
        $this->table_number = '';
    }

    public function store()
    {
        $this->validate([
            'table_number' => 'required|string|max:255',
        ]);

        $data = ['table_number' => $this->table_number];

        // Hanya buat barcode identifier baru jika ini adalah meja baru
        if (!$this->table_id) {
            $data['barcode_identifier'] = Str::uuid();
        }

        Table::updateOrCreate(['id' => $this->table_id], $data);

        session()->flash('message',
            $this->table_id ? 'Meja berhasil diperbarui.' : 'Meja berhasil ditambahkan.');

        $this->closeModal();
    }

    public function delete($id)
    {
        Table::find($id)->delete();
        session()->flash('message', 'Meja berhasil dihapus.');
    }
}
