<x-guest-layout>
    <div class="container p-6 mx-auto my-12 bg-white rounded-lg shadow-lg max-w-2xl">
        <div class="text-center">
            <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h1 class="mt-4 text-3xl font-bold text-gray-800">Pesanan Berhasil Dibuat!</h1>
            <p class="mt-2 text-gray-600">Terima kasih telah memesan. Pesanan Anda sedang kami siapkan.</p>
        </div>

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

        <div class="mt-8">
            <h3 class="mb-4 text-xl font-semibold text-gray-800">Rincian Pesanan</h3>
            <div class="space-y-3">
                @foreach ($order->orderDetails as $detail)
                    <div class="flex justify-between">
                        {{-- PERBAIKAN KECIL: Tampilkan harga per item yang sudah didiskon --}}
                        <span class="text-gray-700">{{ $detail->quantity }}x {{ $detail->menu->name }}</span>
                        <span class="font-medium text-gray-800">Rp
                            {{ number_format($detail->price_at_order * $detail->quantity) }}</span>
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
</x-guest-layout>
