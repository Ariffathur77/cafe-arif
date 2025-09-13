<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Riwayat Transaksi Stok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 mb-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
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
                    <div>
                        <label for="selected_item_id" class="text-sm font-medium text-gray-700">Filter Item</label>
                        <select wire:model.live="selected_item_id" id="selected_item_id"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="all">Semua Item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
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
                                    Waktu</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Nama Item</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Jenis Transaksi</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $trx)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $trx->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $trx->inventoryItem->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if ($trx->type == 'stock_in') bg-green-100 text-green-800
                                            @elseif($trx->type == 'sale') bg-blue-100 text-blue-800
                                            @elseif($trx->type == 'waste') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $trx->type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span
                                            class="text-sm font-semibold {{ $trx->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $trx->quantity > 0 ? '+' : '' }}{{ $trx->quantity }}
                                            {{ $trx->inventoryItem->unit }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $trx->notes }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data
                                        transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
