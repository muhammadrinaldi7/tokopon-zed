<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class OrderHistory extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.app', ['title' => 'Pesanan Saya - TokoPun'])]
    public function render()
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['items.variant.product.media'])
            ->orderByDesc('created_at');

        if ($this->statusFilter) {
            $query->where('order_status', $this->statusFilter);
        }

        return view('livewire.pages.order-history', [
            'orders' => $query->paginate(10),
        ]);
    }
}
