<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderDetail extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $this->order = $order->load(['items.variant.product.media', 'payments', 'shipping']);
    }

    public function confirmReceived(): void
    {
        if ($this->order->order_status !== 'SHIPPED') return;

        $this->order->update(['order_status' => 'COMPLETED']);
        $this->order->refresh();

        $this->dispatch('toast', title: 'Pesanan Selesai', message: 'Terima kasih! Pesanan telah dikonfirmasi.', type: 'success');
    }

    #[Layout('layouts.app', ['title' => 'Detail Pesanan - TokoPun'])]
    public function render()
    {
        return view('livewire.pages.order-detail');
    }
}
