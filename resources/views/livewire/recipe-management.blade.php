<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Kelola Resep untuk: <span class="font-bold">{{ $menu->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session()->has('message'))
                <div class="px-4 py-3 mb-4 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md"
                    role="alert">
                    <p class="font-bold">{{ session('message') }}</p>
                </div>
            @endif

            <div class="p-6 mb-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Tambah Bahan Baku</h3>
                <form wire:submit.prevent="addIngredient" class="mt-4 space-y-4 md:space-y-0 md:flex md:space-x-4">
                    <div class="flex-1">
                        <label for="inventory_item_id" class="sr-only">Bahan Baku</label>
                        <select wire:model="inventory_item_id" id="inventory_item_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Pilih Bahan Baku...</option>
                            @foreach ($inventory_items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                            @endforeach
                        </select>
                        @error('inventory_item_id')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex-1">
                        <label for="quantity_used" class="sr-only">Jumlah Digunakan</label>
                        <input type="number" step="0.01" wire:model="quantity_used" id="quantity_used"
                            placeholder="Jumlah (e.g., 10)"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('quantity_used')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <button type="submit"
                            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Tambah</button>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-6 border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Nama Bahan</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Jumlah Digunakan</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recipes as $recipe)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $recipe->inventoryItem->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $recipe->quantity_used }}
                                            {{ $recipe->inventoryItem->unit }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <button wire:click="removeIngredient({{ $recipe->id }})" @confirm('Yakin hapus bahan ini dari resep?')
                                            class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                        Belum ada resep untuk menu ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
