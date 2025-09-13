<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

                <div class="p-6">
                    @if (session()->has('message'))
                        <div class="px-4 py-3 mb-4 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md"
                            role="alert">
                            <p class="font-bold">{{ session('message') }}</p>
                        </div>
                    @endif

                    <button wire:click="create()"
                        class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                        + Tambah Item Baru
                    </button>
                </div>

                {{-- Modal Form CRUD Item (tetap sama) --}}
                @if ($isModalOpen)
                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div
                            class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div
                                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    {{ $item_id ? 'Edit Item' : 'Tambah Item Baru' }}</h3>
                                <form>
                                    <div class="mt-4">
                                        <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama
                                            Item</label>
                                        <input type="text" wire:model.defer="name" id="name"
                                            placeholder="Contoh: Biji Kopi Arabika"
                                            class="w-full px-3 py-2 border rounded">
                                        @error('name')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="unit"
                                            class="block mb-2 text-sm font-bold text-gray-700">Satuan</label>
                                        <input type="text" wire:model.defer="unit" id="unit"
                                            placeholder="Contoh: gram, ml, pcs" class="w-full px-3 py-2 border rounded">
                                        @error('unit')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="current_stock"
                                            class="block mb-2 text-sm font-bold text-gray-700">Stok Saat Ini</label>
                                        <input type="number" step="0.01" wire:model.defer="current_stock"
                                            id="current_stock" class="w-full px-3 py-2 border rounded">
                                        @error('current_stock')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="low_stock_threshold"
                                            class="block mb-2 text-sm font-bold text-gray-700">Ambang Batas Stok
                                            Rendah</label>
                                        <input type="number" step="0.01" wire:model.defer="low_stock_threshold"
                                            id="low_stock_threshold" class="w-full px-3 py-2 border rounded">
                                        @error('low_stock_threshold')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="px-4 py-3 mt-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" wire:click.prevent="store()"
                                            class="inline-flex justify-center w-full px-4 py-2 text-white bg-blue-600 border rounded-md sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                        <button type="button" wire:click="closeModal()"
                                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-gray-700 bg-white border rounded-md sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- MODAL BARU: Form Transaksi Stok --}}
                @if ($isStockModalOpen)
                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div
                            class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div
                                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Transaksi Stok: <span
                                        class="font-bold">{{ $selected_item_name }}</span></h3>
                                <form>
                                    <div class="mt-4">
                                        <label for="transaction_type"
                                            class="block mb-2 text-sm font-bold text-gray-700">Jenis Transaksi</label>
                                        <select wire:model.live="transaction_type" id="transaction_type"
                                            class="w-full px-3 py-2 border rounded">
                                            <option value="stock_in">Stok Masuk (Pembelian)</option>
                                            <option value="waste">Stok Keluar (Rusak/Buang)</option>
                                            <option value="adjustment">Penyesuaian (Adjustment)</option>
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label for="transaction_quantity"
                                            class="block mb-2 text-sm font-bold text-gray-700">Jumlah</label>
                                        <input type="number" step="0.01" wire:model.defer="transaction_quantity"
                                            id="transaction_quantity" class="w-full px-3 py-2 border rounded">
                                        @error('transaction_quantity')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="transaction_notes"
                                            class="block mb-2 text-sm font-bold text-gray-700">Catatan
                                            (Opsional)</label>
                                        <textarea wire:model.defer="transaction_notes" id="transaction_notes" class="w-full px-3 py-2 border rounded"
                                            placeholder="Contoh: Pembelian dari Supplier B"></textarea>
                                    </div>
                                    <div class="px-4 py-3 mt-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" wire:click.prevent="saveStockTransaction()"
                                            class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-600 border rounded-md sm:ml-3 sm:w-auto sm:text-sm">Simpan
                                            Transaksi</button>
                                        <button type="button" wire:click="closeModal()"
                                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-gray-700 bg-white border rounded-md sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tabel Data --}}
                <div class="p-6 border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Nama Item
                                </th>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Stok Saat
                                    Ini</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm font-bold @if ($item->current_stock <= $item->low_stock_threshold) text-red-600 @else text-gray-900 @endif">
                                            {{ $item->current_stock }} {{ $item->unit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right">
                                        {{-- TOMBOL BARU UNTUK TRANSAKSI STOK --}}
                                        <button wire:click="openStockModal({{ $item->id }})"
                                            class="px-2 py-1 text-xs text-white bg-gray-500 rounded hover:bg-gray-700">Transaksi
                                            Stok</button>
                                        <button wire:click="edit({{ $item->id }})"
                                            class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="delete({{ $item->id }})" @confirm('Yakin ingin hapus item ini?')
                                            class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data
                                        inventaris.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
