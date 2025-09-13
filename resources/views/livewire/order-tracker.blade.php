<div wire:poll.5s>
    <div class="container p-6 mx-auto my-12 bg-white rounded-lg shadow-lg max-w-2xl">
        <div class="text-center">
            @if ($order->status == 'pending')
                <svg class="w-16 h-16 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="mt-4 text-3xl font-bold text-gray-800">Pesanan Diterima</h1>
                <p class="mt-2 text-gray-600">Terima kasih, pesanan Anda akan segera kami proses.</p>
            @elseif($order->status == 'processing')
                <svg class="w-16 h-16 mx-auto text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <h1 class="mt-4 text-3xl font-bold text-gray-800">Pesanan Sedang Diproses</h1>
                <p class="mt-2 text-gray-600">Harap tunggu sebentar, pesanan Anda sedang kami siapkan.</p>
            @else
                <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="mt-4 text-3xl font-bold text-gray-800">Pesanan Selesai!</h1>
                <p class="mt-2 text-gray-600">Pesanan Anda sudah siap. Terima kasih!</p>
            @endif
        </div>

        {{-- BAGIAN YANG DIKEMBALIKAN --}}
        <div class="p-6 mt-8 border-t border-b">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-600">KODE PESANAN</p>
                    <p class="font-mono text-lg font-bold text-gray-800">{{ $order->order_code }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-600">NAMA PEMESAN</p>
                    <p class="text-lg text-gray-800">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">NOMOR MEJA</p>
                    <p class="text-lg text-gray-800">{{ $order->table->table_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-600">WAKTU</p>
                    <p class="text-lg text-gray-800">{{ $order->created_at->format('H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- BAGIAN YANG DIKEMBALIKAN --}}
        <div class="mt-8">
            <h3 class="mb-4 text-xl font-semibold text-gray-800">Rincian Pesanan</h3>
            <div class="space-y-3">
                @foreach ($order->orderDetails as $detail)
                    <div class="flex justify-between">
                        <span class="text-gray-700">{{ $detail->quantity }}x {{ $detail->menu->name }}</span>

                        {{-- Tambahkan logika untuk menampilkan harga asli & diskon --}}
                        <div class="text-right">
                            @if ($detail->price_at_order < $detail->menu->price)
                                <p class="font-medium text-gray-800">Rp
                                    {{ number_format($detail->price_at_order * $detail->quantity) }}</p>
                                <p class="text-xs text-gray-400 line-through">Rp
                                    {{ number_format($detail->menu->price * $detail->quantity) }}</p>
                            @else
                                <p class="font-medium text-gray-800">Rp
                                    {{ number_format($detail->price_at_order * $detail->quantity) }}</p>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="pt-4 mt-4 border-t">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal (Harga Asli)</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($order->total_amount) }}</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($order->discount_amount) }}</span>
                    </div>
                @endif
                <div class="flex justify-between mt-2 text-xl font-bold">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($order->final_amount) }}</span>
                </div>
            </div>
        </div>

        <div class="p-6 mt-8 text-center bg-gray-100 rounded-lg">
            <p class="font-semibold">Silakan lakukan pembayaran di kasir dengan menunjukkan halaman ini.</p>
        </div>
    </div>
</div>
