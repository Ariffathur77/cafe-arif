<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Promo;
use App\Models\Menu;
use Carbon\Carbon;

class PromoManagement extends Component
{
    public $promo_id, $code, $description, $type, $value, $start_date, $end_date, $is_active;
    public $isModalOpen = false;

    // Properti untuk menampung pilihan menu
    public $allMenus;
    public $selected_menus = [];

    public function mount()
    {
        // Nilai default untuk form
        $this->is_active = true;
        $this->type = 'percentage';
        // Ambil semua menu untuk ditampilkan di dropdown
        $this->allMenus = Menu::orderBy('name')->get();
    }

    public function render()
    {
        $promos = Promo::latest()->get();
        return view('livewire.promo-management', ['promos' => $promos])
            ->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $promo = Promo::with('menus')->findOrFail($id); // Eager load relasi menu

        $this->promo_id = $id;
        $this->code = $promo->code;
        $this->description = $promo->description;
        $this->type = $promo->type;
        $this->value = $promo->value;
        $this->is_active = $promo->is_active;

        // Format tanggal untuk input type="date"
        $this->start_date = $promo->start_date ? Carbon::parse($promo->start_date)->format('Y-m-d') : null;
        $this->end_date = $promo->end_date ? Carbon::parse($promo->end_date)->format('Y-m-d') : null;

        // Muat menu yang sudah terhubung dengan promo ini
        $this->selected_menus = $promo->menus->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'code' => 'required|string|unique:promos,code,' . $this->promo_id,
            'description' => 'required|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date', // Dibuat nullable agar fleksibel
            'end_date' => 'nullable|date|after_or_equal:start_date', // Dibuat nullable
            'is_active' => 'required|boolean',
        ]);

        // Simpan atau update data promo utama
        $promo = Promo::updateOrCreate(['id' => $this->promo_id], [
            'code' => strtoupper($this->code),
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);

        // Sinkronkan relasi dengan menu di tabel pivot
        $promo->menus()->sync($this->selected_menus);

        session()->flash('message', $this->promo_id ? 'Promo berhasil diperbarui.' : 'Promo berhasil dibuat.');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->promo_id = null;
        $this->code = '';
        $this->description = '';
        $this->type = 'percentage';
        $this->value = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->is_active = true;
        $this->selected_menus = []; // Reset pilihan menu
    }

    public function delete($id)
    {
        Promo::find($id)->delete();
        session()->flash('message', 'Promo berhasil dihapus.');
    }
}
