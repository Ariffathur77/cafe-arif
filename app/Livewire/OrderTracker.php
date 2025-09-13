<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderTracker extends Component
{
    public Order $order;

    // Livewire akan otomatis mengisi $order dari parameter rute
    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        // Muat ulang data order setiap kali komponen di-refresh oleh polling
        $this->order->refresh();

        return view('livewire.order-tracker')->layout('layouts.guest');
    }
}
