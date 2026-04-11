<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderDetail extends Component
{
    public Order $order;

    public $showReviewModal = false;
    public $reviewItemId = null;
    public $reviewRating = 5;
    public $reviewComment = '';

    public function mount(Order $order): void
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $this->order = $order->load(['items.variant.product.media', 'items.review', 'payments', 'shipping']);
    }

    public function confirmReceived(): void
    {
        if ($this->order->order_status !== 'SHIPPED') return;

        $this->order->update(['order_status' => 'COMPLETED']);
        $this->order->refresh();

        $this->dispatch('toast', title: 'Pesanan Selesai', message: 'Terima kasih! Pesanan telah dikonfirmasi.', type: 'success');
    }

    public function openReviewModal($itemId)
    {
        $this->reviewItemId = $itemId;
        $this->reviewRating = 5;
        $this->reviewComment = '';
        $this->showReviewModal = true;
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->reviewItemId = null;
    }

    public function submitReview()
    {
        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComment' => 'nullable|string|max:1000',
        ]);

        $item = collect($this->order->items)->firstWhere('id', $this->reviewItemId);
        if (!$item || $item->review) return;

        \App\Models\ProductReview::create([
            'user_id' => Auth::id(),
            'product_id' => $item->variant->product_id,
            'order_item_id' => $item->id,
            'rating' => $this->reviewRating,
            'comment' => $this->reviewComment,
        ]);

        $this->order->refresh();
        $this->closeReviewModal();
        $this->dispatch('toast', title: 'Berhasil', message: 'Ulasan produk berhasil dikirim.', type: 'success');
    }

    #[Layout('layouts.app', ['title' => 'Detail Pesanan - TokoPun'])]
    public function render()
    {
        return view('livewire.pages.order-detail');
    }
}
