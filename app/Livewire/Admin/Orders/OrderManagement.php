<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    // Ubah status pesanan
    public function updateOrderStatus(int $orderId, string $status): void
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['order_status' => $status]);
            $this->dispatch('toast', title: 'Berhasil', message: "Status pesanan diubah ke $status", type: 'success');
        }
    }

    #[Layout('layouts.admin', ['title' => 'Kelola Pesanan'])]
    public function render()
    {
        $query = Order::with(['user', 'items', 'shipping'])
            ->orderByDesc('created_at');

        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->statusFilter) {
            $query->where('order_status', $this->statusFilter);
        }

        return view('livewire.admin.orders.order-management', [
            'orders' => $query->paginate(10),
        ]);
    }
}
