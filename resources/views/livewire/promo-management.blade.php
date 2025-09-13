<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen Promo & Diskon') }}
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
                        class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">+ Tambah Promo
                        Baru</button>
                </div>

                @if ($isModalOpen)
                    @include('livewire.promo-form')
                @endif

                <div class="p-6 border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Deskripsi
                                </th>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Nilai</th>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Status</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($promos as $promo)
                                <tr>
                                    <td class="px-6 py-4"><span
                                            class="px-2 py-1 font-mono text-sm text-indigo-800 bg-indigo-100 rounded">{{ $promo->code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $promo->description }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $promo->type == 'percentage' ? $promo->value . ' %' : 'Rp ' . number_format($promo->value) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($promo->is_active && now()->between($promo->start_date, $promo->end_date))
                                            <span
                                                class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Tidak
                                                Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right">
                                        <button wire:click="edit({{ $promo->id }})"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="delete({{ $promo->id }})" @confirm('Yakin hapus promo ini?')
                                            class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data promo.
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
