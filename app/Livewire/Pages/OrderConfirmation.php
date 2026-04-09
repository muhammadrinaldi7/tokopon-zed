<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $this->order = $order->load(['items.variant.product.media', 'payments']);
    }

    #[Layout('layouts.app', ['title' => 'Pesanan Berhasil - TokoPun'])]
    public function render()
    {
        return view('livewire.pages.order-confirmation');
    }
}
