<div wire:poll.30s>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($salesToday) }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-sm font-medium text-gray-500">Pesanan Hari Ini</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $ordersTodayCount }}</p>
                </div>
                <div class="p-6 {{ $lowStockItems->count() > 0 ? 'bg-red-100' : 'bg-white' }} rounded-lg shadow-lg">
                    <h3
                        class="text-sm font-medium {{ $lowStockItems->count() > 0 ? 'text-red-800' : 'text-gray-500' }}">
                        Item Stok Menipis</h3>
                    <p
                        class="mt-2 text-3xl font-bold {{ $lowStockItems->count() > 0 ? 'text-red-900' : 'text-gray-900' }}">
                        {{ $lowStockItems->count() }}</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="p-6 mb-6 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Grafik Pendapatan</h3>
                    <div class="flex space-x-2">
                        <button wire:click="$set('filter','daily')"
                            class="{{ $filter == 'daily' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} px-3 py-1 text-sm rounded-md">Harian</button>
                        <button wire:click="$set('filter','weekly')"
                            class="{{ $filter == 'weekly' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} px-3 py-1 text-sm rounded-md">Mingguan</button>
                        <button wire:click="$set('filter','monthly')"
                            class="{{ $filter == 'monthly' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} px-3 py-1 text-sm rounded-md">Bulanan</button>
                    </div>
                </div>
                <div class="w-full h-80">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Pesanan Terbaru & Stok --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
                    <div class="space-y-4">
                        @forelse($recentOrders as $order)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div>
                                    <p class="font-semibold text-gray-700">Meja
                                        {{ $order->table?->table_number ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="font-bold text-gray-800">Rp {{ number_format($order->final_amount) }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada pesanan.</p>
                        @endforelse
                    </div>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Perlu Restock</h3>
                    <div class="space-y-3">
                        @forelse($lowStockItems as $item)
                            <div class="flex items-center justify-between text-sm">
                                <p class="text-gray-700">{{ $item->name }}</p>
                                <p class="font-bold text-red-600">{{ $item->current_stock }} {{ $item->unit }}</p>
                            </div>
                        @empty
                            <p class="text-green-600">👍 Stok aman!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                let salesChart = null; // Pindahkan deklarasi ke sini

                // Fungsi untuk membuat atau membuat ulang chart
                function createOrUpdateChart(labels, data, type) {
                    // SOLUSI: Hancurkan chart yang ada sebelum membuat yang baru
                    if (salesChart) {
                        salesChart.destroy();
                    }

                    const ctx = document.getElementById('salesChart').getContext('2d');
                    salesChart = new Chart(ctx, {
                        type: type,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Pendapatan',
                                data: data,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                // Atur properti khusus di sini
                                fill: type === 'line', // Hanya 'fill' jika tipe-nya line
                                tension: type === 'line' ? 0.3 :
                                    0 // Hanya ada 'tension' jika tipe-nya line
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: (value) => new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(value)
                                    }
                                }
                            }
                        }
                    });
                }

                // Panggil fungsi di atas saat menerima event dari Livewire
                Livewire.on('refreshChart', ({
                    labels,
                    data,
                    type
                }) => {
                    console.log('Chart Data Received:', labels, data, type);
                    createOrUpdateChart(labels, data, type);
                });
            });
        </script>
    @endpush
</div>
