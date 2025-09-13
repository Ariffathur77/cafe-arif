<div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $promo_id ? 'Edit Promo' : 'Tambah Promo Baru' }}
            </h3>
            <form>
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2">

                    <div>
                        <label for="code" class="block text-sm font-bold text-gray-700">Kode Promo</label>
                        <input type="text" wire:model.defer="code" id="code"
                            class="w-full px-3 py-2 border rounded">
                        @error('code')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700">Jenis Promo</label>
                        <select wire:model.live="type" id="type" class="w-full px-3 py-2 border rounded">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed_amount">Potongan Tetap (Rp)</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-bold text-gray-700">Deskripsi</label>
                        <input type="text" wire:model.defer="description" id="description"
                            class="w-full px-3 py-2 border rounded">
                        @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="value" class="block text-sm font-bold text-gray-700">Nilai</label>
                        <input type="number" wire:model.defer="value" id="value"
                            class="w-full px-3 py-2 border rounded"
                            placeholder="{{ $type == 'percentage' ? 'Contoh: 10 untuk 10%' : 'Contoh: 10000 untuk Rp 10.000' }}">
                        @error('value')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-bold text-gray-700">Status</label>
                        <select wire:model.defer="is_active" id="is_active" class="w-full px-3 py-2 border rounded">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-bold text-gray-700">Tanggal Mulai</label>
                        <input type="date" wire:model.defer="start_date" id="start_date"
                            class="w-full px-3 py-2 border rounded">
                        @error('start_date')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-bold text-gray-700">Tanggal Selesai</label>
                        <input type="date" wire:model.defer="end_date" id="end_date"
                            class="w-full px-3 py-2 border rounded">
                        @error('end_date')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- PINDAHKAN BLOK INI KE DALAM GRID --}}
                    <div class="sm:col-span-2">
                        <label for="selected_menus" class="block text-sm font-bold text-gray-700">Berlaku untuk Menu
                            (kosongkan jika untuk semua)</label>
                        <p class="text-xs text-gray-500">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</p>
                        <select multiple wire:model.defer="selected_menus" id="selected_menus"
                            class="w-full h-32 px-3 py-2 mt-1 border rounded">
                            @foreach ($allMenus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>

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
