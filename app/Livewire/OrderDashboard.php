<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Payment;

class OrderDashboard extends Component
{
    public $isPaymentModalOpen = false;
    public ?Order $selectedOrder = null;
    public $amount_paid;
    public $change = 0;

    public function render()
    {
        $orders = Order::with('table', 'orderDetails.menu')
                       ->whereNotIn('status', ['completed', 'paid', 'cancelled'])
                       ->latest()
                       ->get();

        return view('livewire.order-dashboard', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }

    // Fungsi ini dipanggil dari Alpine.js di frontend
    public function calculateChange($value)
    {
        // Bersihkan format non-numerik
        $cleanedValue = preg_replace('/[^0-9]/', '', $value);
        $amount = floatval($cleanedValue);

        if ($this->selectedOrder && is_numeric($amount) && $amount >= $this->selectedOrder->final_amount) {
            $this->change = $amount - $this->selectedOrder->final_amount;
        } else {
            $this->change = 0;
        }
        $this->amount_paid = $cleanedValue;
    }

    public function openPaymentModal($orderId)
    {
        $this->selectedOrder = Order::with('table')->findOrFail($orderId);
        $this->isPaymentModalOpen = true;
    }

    public function closeModal()
    {
        $this->isPaymentModalOpen = false;
        $this->reset(['selectedOrder', 'amount_paid', 'change']);
    }

    public function processPayment()
    {
        $this->validate(['amount_paid' => 'required|numeric|min:'.$this->selectedOrder->final_amount]);

        Payment::create([
            'order_id' => $this->selectedOrder->id,
            'amount_paid' => $this->amount_paid,
            'payment_method' => 'Cash',
        ]);

        $this->selectedOrder->status = 'paid';
        $this->selectedOrder->save();

        $this->selectedOrder->table->status = 'available';
        $this->selectedOrder->table->save();

        $orderId = $this->selectedOrder->id; // Simpan ID sebelum di-reset

        $this->closeModal();

        // 4. Redirect ke halaman struk
        return redirect()->route('admin.orders.receipt', $orderId);
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->save();
        }
    }
}
