<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 mb-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="start_date" class="text-sm font-medium text-gray-700">Dari Tanggal</label>
                            <input type="date" wire:model.live="start_date" id="start_date"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="text-sm font-medium text-gray-700">Sampai Tanggal</label>
                            <input type="date" wire:model.live="end_date" id="end_date"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="p-4 text-center bg-green-100 rounded-lg">
                        <h3 class="text-sm font-medium text-green-800 uppercase">Total Pendapatan</h3>
                        <p class="mt-1 text-2xl font-semibold text-green-900">
                            Rp {{ number_format($total_revenue, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Waktu Pesanan</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Kode Pesanan</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Pelanggan</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                    Harga Asli</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                    Diskon</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                    Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                                    {{-- DATA BARU YANG DITAMBAHKAN --}}
                                    <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-red-600 whitespace-nowrap">
                                        - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-right text-gray-900 whitespace-nowrap">
                                        Rp {{ number_format($order->final_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Jangan lupa ubah colspan menjadi 6 --}}
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                        Tidak ada data penjualan pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
