<div wire:poll.5s>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Status Meja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-6">

                @foreach ($tables as $table)
                    <div
                        class="flex flex-col items-center justify-center p-6 text-white rounded-lg shadow-lg
                        {{ $table->status == 'available' ? 'bg-green-500' : 'bg-red-500' }}">

                        <div class="text-3xl font-bold">
                            {{ $table->table_number }}
                        </div>
                        <div class="mt-2 text-sm font-medium">
                            {{ ucfirst($table->status) }}
                        </div>

                        <div class="mt-4">
                            @if ($table->status == 'occupied')
                                <button wire:click="updateStatus({{ $table->id }}, 'available')"
                                    class="px-3 py-1 text-xs bg-white border border-transparent rounded-full text-red-600 hover:bg-red-100">
                                    Kosongkan
                                </button>
                            @else
                                <button wire:click="updateStatus({{ $table->id }}, 'occupied')"
                                    class="px-3 py-1 text-xs bg-white border border-transparent rounded-full text-green-600 hover:bg-green-100">
                                    Isi
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
