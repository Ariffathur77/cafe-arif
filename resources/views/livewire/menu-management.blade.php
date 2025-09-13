<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

                <div class="p-6">
                    @if (session()->has('message'))
                        <div class="px-4 py-3 mb-4 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md"
                            role="alert">
                            <div class="flex">
                                <div>
                                    <p class="font-bold">{{ session('message') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <button wire:click="create()"
                            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            + Tambah Menu Baru
                        </button>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full max-w-md px-3 py-2 leading-tight text-gray-700 border rounded shadow-sm appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Cari nama menu atau kategori...">
                    </div>
                </div>

                {{-- Modal Form (Create & Update) --}}
                @if ($isModalOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true">
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>

                            <div
                                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    {{ $menu_id ? 'Edit Menu' : 'Tambah Menu Baru' }}
                                </h3>
                                <form wire:submit.prevent="store">
                                    <div class="mt-4">
                                        <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama
                                            Menu</label>
                                        <input type="text" wire:model.defer="name" id="name"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                        @error('name')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="category_id"
                                            class="block mb-2 text-sm font-bold text-gray-700">Kategori</label>
                                        <select wire:model.defer="category_id" id="category_id"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-4" x-data="{ price: @entangle('price') }">
                                        <label for="price"
                                            class="block mb-2 text-sm font-bold text-gray-700">Harga</label>

                                        <div class="relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>

                                            <input type="text" id="price" x-model="price"
                                                @input="price = Number($event.target.value.replace(/\D/g,'')).toLocaleString('id-ID')"
                                                class="block w-full px-3 py-2 pl-8 pr-4 leading-tight text-gray-700 border rounded appearance-none focus:outline-none focus:shadow-outline"
                                                placeholder="0">
                                        </div>

                                        @error('price')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="description"
                                            class="block mb-2 text-sm font-bold text-gray-700">Deskripsi</label>
                                        <textarea wire:model.defer="description" id="description"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"></textarea>
                                    </div>

                                    <div class="mt-4">
                                        <label for="image" class="block mb-2 text-sm font-bold text-gray-700">Gambar
                                            Menu</label>
                                        <input type="file" wire:model="image" id="image"
                                            class="w-full text-sm border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('image')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="image" class="mt-2 text-sm text-gray-500">
                                            Uploading...</div>

                                        <div class="mt-4">
                                            @if ($image)
                                                <p class="mb-2 text-sm font-medium">Pratinjau Gambar Baru:</p>
                                                <img src="{{ $image->temporaryUrl() }}"
                                                    class="object-cover w-40 h-40 rounded">
                                            @elseif ($existingImageUrl)
                                                <p class="mb-2 text-sm font-medium">Gambar Saat Ini:</p>
                                                <img src="{{ asset('storage/' . $existingImageUrl) }}"
                                                    class="object-cover w-40 h-40 rounded">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 mt-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Simpan
                                        </button>
                                        <button type="button" wire:click="closeModal()"
                                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tabel Data Menu --}}
                <div class="p-6 border-t border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Gambar</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Nama Menu</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Kategori</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($menus as $menu)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($menu->image_url)
                                                <img src="{{ asset('storage/' . $menu->image_url) }}"
                                                    alt="{{ $menu->name }}" class="object-cover w-16 h-16 rounded">
                                            @else
                                                <div
                                                    class="flex items-center justify-center w-16 h-16 text-xs text-gray-400 bg-gray-100 rounded">
                                                    No Image</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">{{ $menu->category->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp
                                                {{ number_format($menu->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <a href="#" class="text-green-600 hover:text-green-900">Resep</a>
                                            <button wire:click="edit({{ $menu->id }})"
                                                class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <button wire:click="delete({{ $menu->id }})" @confirm('Yakin ingin hapus menu ini?')
                                                class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                            Tidak ada data menu yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200">
                    {{ $menus->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
