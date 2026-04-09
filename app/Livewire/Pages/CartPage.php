<?php

namespace App\Livewire\Pages;

use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class CartPage extends Component
{
    #[Layout('layouts.app', ['title' => 'Keranjang Belanja - TokoPun'])]

    public function updateQty(int $cartItemId, int $qty): void
    {
        $cartService = app(CartService::class);
        $cartService->updateQty($cartItemId, $qty);

        $this->dispatch('cart-updated');
    }

    public function incrementQty(int $cartItemId): void
    {
        $cart = app(CartService::class)->getCart();
        if (!$cart) return;

        $item = $cart->items->firstWhere('id', $cartItemId);
        if (!$item) return;

        $maxStock = $item->productVariant->stock ?? 0;
        if ($item->qty < $maxStock) {
            app(CartService::class)->updateQty($cartItemId, $item->qty + 1);
            $this->dispatch('cart-updated');
        }
    }

    public function decrementQty(int $cartItemId): void
    {
        $cart = app(CartService::class)->getCart();
        if (!$cart) return;

        $item = $cart->items->firstWhere('id', $cartItemId);
        if (!$item) return;

        if ($item->qty > 1) {
            app(CartService::class)->updateQty($cartItemId, $item->qty - 1);
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem(int $cartItemId): void
    {
        app(CartService::class)->removeItem($cartItemId);
        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        app(CartService::class)->clear();
        $this->dispatch('cart-updated');
    }

    #[On('cart-updated')]
    public function render()
    {
        $cart = app(CartService::class)->getCart();
        $items = $cart ? $cart->items->load(['productVariant.product.media', 'productVariant.product.brand']) : collect();

        $totalPrice = $items->sum(fn($item) => $item->qty * $item->productVariant->price);
        $totalItems = $items->sum('qty');

        return view('livewire.pages.cart-page', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
        ]);
    }
}
