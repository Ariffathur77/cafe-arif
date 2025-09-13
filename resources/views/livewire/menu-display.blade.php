<div>
    <div class="container p-4 mx-auto md:p-6">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800">Selamat Datang di Meja {{ $table->table_number }}</h1>
            <p class="text-lg text-gray-600">Silakan pilih menu Anda</p>
        </div>

        @if (session()->has('message'))
            <div class="px-4 py-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="px-4 py-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:space-x-8">
            {{-- KOLOM KIRI: DAFTAR MENU --}}
            <div class="w-full md:w-2/3">
                @foreach ($categories as $category)
                    <div class="mb-10">
                        <h2 class="pb-2 mb-4 text-2xl font-semibold text-gray-700 border-b-2 border-gray-200">
                            {{ $category->name }}</h2>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @foreach ($category->menus as $menu)
                                <div class="flex flex-col overflow-hidden bg-white border rounded-lg shadow-md">
                                    @if ($menu->image_url)
                                        <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}"
                                            class="object-cover w-full h-40">
                                    @else
                                        <div class="flex items-center justify-center w-full h-40 bg-gray-200"><span
                                                class="text-gray-500">Gambar tidak tersedia</span></div>
                                    @endif
                                    <div class="flex flex-col flex-grow p-4">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $menu->name }}</h3>
                                        <p class="mt-1 text-sm text-gray-600 flex-grow">{{ $menu->description }}</p>
                                        <div class="flex items-center justify-between mt-4">
                                            <div>
                                                @if (isset($menu->final_price) && $menu->final_price < $menu->original_price)
                                                    <span class="text-xl font-bold text-red-600">Rp
                                                        {{ number_format($menu->final_price) }}</span>
                                                    <span class="ml-2 text-sm text-gray-500 line-through">Rp
                                                        {{ number_format($menu->original_price) }}</span>
                                                @else
                                                    <span class="text-xl font-semibold text-gray-900">Rp
                                                        {{ number_format($menu->price) }}</span>
                                                @endif
                                            </div>
                                            <button
                                                wire:click="addToCart({{ $menu->id }}, {{ $menu->final_price ?? $menu->price }})"
                                                class="px-3 py-2 text-sm font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-700">
                                                + Keranjang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- KOLOM KANAN: KERANJANG BELANJA --}}
            <div class="w-full mt-8 md:w-1/3 md:mt-0">
                <div class="sticky top-8">
                    <div class="p-6 bg-white border rounded-lg shadow-md">
                        <h2 class="pb-2 mb-4 text-xl font-semibold text-center border-b">Keranjang Anda</h2>
                        @forelse($cart as $item)
                            <div class="flex items-center justify-between py-2 border-b">
                                <div>
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item['price']) }}</p>
                                </div>
                                <div class="flex items-center">
                                    <button wire:click="decreaseQuantity({{ $item['id'] }})"
                                        class="px-2 py-1 text-white bg-gray-400 rounded">-</button>
                                    <span class="px-3">{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQuantity({{ $item['id'] }})"
                                        class="px-2 py-1 text-white bg-gray-400 rounded">+</button>
                                    <button wire:click="removeFromCart({{ $item['id'] }})"
                                        class="ml-4 text-red-500 hover:text-red-700">×</button>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-center text-gray-500">Keranjang masih kosong.</p>
                        @endforelse

                        @if (!empty($cart))
                            <div class="mt-6">
                                <div class="mb-4">
                                    <label for="customer_name" class="block mb-2 text-sm font-bold text-gray-700">Nama
                                        Anda</label>
                                    <input type="text" wire:model="customer_name" id="customer_name"
                                        class="w-full px-3 py-2 border rounded" placeholder="Masukkan nama Anda">
                                    @error('customer_name')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-between pt-4 mt-4 text-xl font-bold border-t">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($this->total) }}</span>
                                </div>

                                {{-- Tombol Checkout yang diperbarui dengan konfirmasi --}}
                                <button wire:click="$dispatch('openCheckoutConfirmation')" wire:loading.attr="disabled"
                                    wire:target="$dispatch('openCheckoutConfirmation')"
                                    class="flex items-center justify-center w-full px-4 py-2 mt-6 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-wait">
                                    <svg wire:loading wire:target="$dispatch('openCheckoutConfirmation')"
                                        class="w-5 h-5 mr-3 -ml-1 text-white animate-spin"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span wire:loading.remove wire:target="$dispatch('openCheckoutConfirmation')">Pesan
                                        Sekarang</span>
                                    <span wire:loading wire:target="$dispatch('openCheckoutConfirmation')">Memuat
                                        Konfirmasi...</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI CHECKOUT --}}
    @if ($this->total > 0 && !empty($customer_name))
        <div x-data="{ open: false }" x-init="$wire.on('openCheckoutConfirmation', () => open = true)">
            <div x-show="open"
                class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div x-show="open" @click.away="open = false"
                    class="w-full max-w-lg p-6 mx-auto bg-white rounded-lg shadow-xl"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <h3 class="mb-4 text-2xl font-bold text-gray-900">Konfirmasi Pesanan</h3>
                    <p class="mb-2 text-gray-700">Pelanggan: <span class="font-semibold">{{ $customer_name }}</span>
                    </p>
                    <p class="mb-4 text-gray-700">Meja: <span class="font-semibold">{{ $table->table_number }}</span>
                    </p>

                    <div class="mb-4 max-h-48 overflow-y-auto border-t border-b py-2">
                        @foreach ($cart as $item)
                            <div class="flex justify-between items-center text-sm text-gray-800 py-1">
                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <p class="mb-6 text-xl font-bold text-right text-gray-900">Total: Rp
                        {{ number_format($this->total) }}</p>

                    <p class="mb-6 text-gray-600">Apakah Anda yakin ingin melanjutkan pesanan ini?</p>

                    <div class="flex justify-end space-x-4">
                        <button @click="open = false"
                            class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button wire:click="checkout" wire:loading.attr="disabled" wire:target="checkout"
                            @click="open = false"
                            class="flex items-center justify-center px-6 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-wait">
                            <svg wire:loading wire:target="checkout"
                                class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="checkout">Ya, Pesan Sekarang</span>
                            <span wire:loading wire:target="checkout">Memproses Pesanan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
