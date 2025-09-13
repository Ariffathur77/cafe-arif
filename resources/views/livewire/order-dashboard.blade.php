<div wire:poll.5s>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($orders as $order)
                    <div class="p-6 bg-white rounded-lg shadow-xl">
                        <div class="flex items-center justify-between pb-4 border-b">
                            <div>
                                <h3 class="text-lg font-bold">Meja {{ $order->table->table_number }}</h3>
                                <p class="text-sm text-gray-600">Pesanan oleh: {{ $order->customer_name }}</p>
                            </div>
                            <span
                                class="px-2 py-1 text-sm font-semibold text-white rounded-full
                                @if ($order->status == 'pending') bg-yellow-500 @endif
                                @if ($order->status == 'processing') bg-blue-500 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="py-4 space-y-2">
                            @foreach ($order->orderDetails as $detail)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $detail->quantity }}x {{ $detail->menu->name }}</span>
                                    <span>Rp {{ number_format($detail->subtotal) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="pt-4 mt-4 border-t">
                            <div class="flex justify-between font-bold">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->final_amount) }}</span>
                            </div>
                        </div>
                        <div class="flex mt-6 space-x-2">
                            @if ($order->status == 'pending')
                                <button wire:click="updateStatus({{ $order->id }}, 'processing')"
                                    class="w-full px-4 py-2 text-sm font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Proses
                                    Pesanan</button>
                            @elseif($order->status == 'processing')
                                <button wire:click="openPaymentModal({{ $order->id }})"
                                    class="w-full px-4 py-2 text-sm font-bold text-white bg-green-500 rounded hover:bg-green-700">Bayar</button>
                            @endif
                            <div class="w-full">
                                <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" @confirm('Yakin batalkan pesanan ini?')
                                    class="w-full px-4 py-2 text-sm font-bold text-white bg-red-500 rounded hover:bg-red-700">Batalkan</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500">
                        <p>Tidak ada pesanan aktif saat ini.</p>
                    </div>
                @endforelse
            </div>

            @if ($isPaymentModalOpen && $selectedOrder)
                <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
                    <div class="w-full max-w-sm p-6 mx-auto bg-white rounded-lg shadow-lg">
                        <h3 class="mb-4 text-xl font-bold text-center">Pembayaran Meja
                            {{ $selectedOrder->table->table_number }}</h3>
                        <div class="py-4 space-y-2 border-t border-b">
                            <div class="flex justify-between font-semibold">
                                <span>Total Tagihan</span>
                                <span>Rp {{ number_format($selectedOrder->final_amount) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <label for="amount_paid" class="font-semibold">Uang Dibayar</label>
                                <div x-data="{ amount: @entangle('amount_paid') }" x-init="$watch('amount', value => $wire.calculateChange(value))" class="relative w-1/2">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                                    <input type="text" id="amount_paid" x-model="amount"
                                        x-mask:dynamic="$money($input, '.')"
                                        class="w-full px-2 py-1 pl-8 text-right border rounded-md">
                                </div>
                            </div>
                            @error('amount_paid')
                                <p class="text-sm text-right text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-between font-semibold">
                                <span>Kembalian</span>
                                <span>Rp {{ number_format($change) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-end mt-6 space-x-4">
                            <button wire:click="closeModal"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
                            <button wire:click="processPayment"
                                class="px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Konfirmasi
                                Bayar & Cetak</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
