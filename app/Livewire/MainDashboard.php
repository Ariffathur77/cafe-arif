<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\InventoryItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class MainDashboard extends Component
{
    public $filter = 'daily';

    // Properti ini akan diisi oleh loadStats() dan tersedia di view
    public $salesToday = 0;
    public $ordersTodayCount = 0;
    public $lowStockItems;
    public $recentOrders;

    public function mount()
    {
        // Inisialisasi koleksi kosong agar tidak error saat render pertama
        $this->lowStockItems = collect();
        $this->recentOrders = collect();

        // Muat data untuk pertama kali
        $this->loadStats();
        $this->getChartData();
    }

    public function updatedFilter()
    {
        $this->getChartData();
    }

    public function loadStats()
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $this->salesToday = Order::whereIn('status', ['completed', 'paid'])
            ->whereDate('created_at', $today)
            ->sum('final_amount');

        $this->ordersTodayCount = Order::whereIn('status', ['completed', 'paid'])
            ->whereDate('created_at', $today)
            ->count();

        $this->lowStockItems = InventoryItem::whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->where('current_stock', '>', 0)
            ->get();

        $this->recentOrders = Order::with('table')->latest()->take(5)->get();
    }

    public function getChartData()
    {
        $statuses = ['completed', 'paid'];
        // KEMBALIKAN LOGIKA INI: bar untuk harian, line untuk lainnya
        $type = $this->filter === 'daily' ? 'bar' : 'line';
        $labels = [];
        $data = [];
        $now = Carbon::now('Asia/Jakarta');

        if ($this->filter === 'daily') {
            $salesTemplate = array_fill_keys(range(0, 23), 0);
            $salesRaw = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(final_amount) as total'))
                ->whereIn('status', $statuses)->whereDate('created_at', $now->toDateString())
                ->groupBy('hour')->pluck('total', 'hour')->all();

            $finalSales = array_replace($salesTemplate, $salesRaw);

            $labels = array_map(fn($h) => sprintf('%02d:00', $h), array_keys($finalSales));
            $data = array_values($finalSales);

        } elseif ($this->filter === 'weekly') {
            $period = CarbonPeriod::create($now->startOfWeek(), $now->endOfWeek());
            $salesTemplate = [];
            foreach ($period as $date) {
                $salesTemplate[$date->toDateString()] = 0;
            }

            $salesRaw = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(final_amount) as total'))
                ->whereIn('status', $statuses)->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()])
                ->groupBy('date')->pluck('total', 'date')->all();

            $finalSales = array_replace($salesTemplate, $salesRaw);
            $labels = array_map(fn($date) => Carbon::parse($date)->format('D'), array_keys($finalSales)); // Sen, Sel, ...
            $data = array_values($finalSales);

        } elseif ($this->filter === 'monthly') {
            $period = CarbonPeriod::create($now->startOfMonth(), $now->endOfMonth());
            $salesTemplate = [];
            foreach ($period as $date) {
                $salesTemplate[$date->toDateString()] = 0;
            }

            $salesRaw = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(final_amount) as total'))
                ->whereIn('status', $statuses)->whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)
                ->groupBy('date')->pluck('total', 'date')->all();

            $finalSales = array_replace($salesTemplate, $salesRaw);
            $labels = array_map(fn($date) => Carbon::parse($date)->format('d M'), array_keys($finalSales)); // 01 Sep, ...
            $data = array_values($finalSales);
        }

        $this->dispatch('refreshChart', labels: $labels, data: $data, type: $type);
    }

    // HANYA ADA SATU METHOD RENDER
    public function render()
    {
        // Panggil loadStats di render agar data statistik (seperti pendapatan hari ini)
        // ikut ter-update jika ada polling
        $this->loadStats();
        return view('livewire.main-dashboard')->layout('layouts.app');
    }
}
