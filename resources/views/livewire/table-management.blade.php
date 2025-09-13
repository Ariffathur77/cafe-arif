<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Meja') }}
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
                        + Tambah Meja Baru
                    </button>
                </div>

                {{-- Modal Form --}}
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
                                    {{ $table_id ? 'Edit Meja' : 'Tambah Meja Baru' }}</h3>
                                <form>
                                    <div class="mt-4">
                                        <label for="table_number"
                                            class="block mb-2 text-sm font-bold text-gray-700">Nomor Meja</label>
                                        <input type="text" wire:model.defer="table_number" id="table_number"
                                            placeholder="Contoh: 01, 02, atau VIP A"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                        @error('table_number')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="px-4 py-3 mt-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" wire:click.prevent="store()"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                        <button type="button" wire:click="closeModal()"
                                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
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
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Nomor Meja</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Barcode Identifier (untuk QR)</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tables as $table)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $table->table_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $table->barcode_identifier }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <a href="{{ route('admin.tables.qr', $table->id) }}" target="_blank"
                                            class="px-2 py-1 text-xs text-white bg-gray-600 rounded hover:bg-gray-800">QR
                                            Code</a>
                                        <button wire:click="edit({{ $table->id }})"
                                            class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="delete({{ $table->id }})" @confirm('Yakin ingin hapus meja ini?')
                                            class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                        Belum ada data meja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
