<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;

class ReportPage extends Component
{
    public $start_date;
    public $end_date;
    public $total_revenue = 0;

    // Fungsi mount akan dijalankan saat komponen dimuat pertama kali
    public function mount()
    {
        // Set tanggal default ke hari ini
        $this->start_date = Carbon::today()->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Query untuk mengambil data order yang sudah selesai/dibayar
        $ordersQuery = Order::with('table')
            ->whereIn('status', ['completed', 'paid'])
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->latest();

        // Ambil hasil query untuk ditampilkan di tabel
        $orders = $ordersQuery->get();

        // Hitung total pendapatan dari query yang sama
        $this->total_revenue = $ordersQuery->sum('final_amount');

        return view('livewire.report-page', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }

    // Fungsi ini akan dipanggil setiap kali filter tanggal diubah
    public function filter()
    {
        // Cukup panggil render() lagi, karena logika filter sudah ada di sana
        $this->render();
    }
}
