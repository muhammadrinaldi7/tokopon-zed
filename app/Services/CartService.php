<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create cart for current user/session.
     */
    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );
        }

        $sessionId = Session::getId();

        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => null]
        );
    }

    /**
     * Get current cart (without creating).
     */
    public function getCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::with(['items.productVariant.product.media'])
                ->where('user_id', Auth::id())
                ->first();
        }

        $sessionId = Session::getId();
        return Cart::with(['items.productVariant.product.media'])
            ->where('session_id', $sessionId)
            ->first();
    }

    /**
     * Add item to cart. If variant already exists, increment qty.
     */
    public function addItem(int $variantId, int $qty = 1, ?string $notes = null): CartItem
    {
        $cart = $this->getOrCreateCart();
        $variant = ProductVariant::findOrFail($variantId);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->qty + $qty;

            // Jangan melebihi stok
            if ($newQty > $variant->stock) {
                $newQty = $variant->stock;
            }

            $cartItem->update(['qty' => $newQty]);
        } else {
            $finalQty = min($qty, $variant->stock);

            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variantId,
                'qty' => $finalQty,
                'notes' => $notes,
            ]);
        }

        return $cartItem->fresh();
    }

    /**
     * Update item qty.
     */
    public function updateQty(int $cartItemId, int $qty): ?CartItem
    {
        $cart = $this->getOrCreateCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->first();

        if (!$cartItem) {
            return null;
        }

        if ($qty <= 0) {
            $cartItem->delete();
            return null;
        }

        // Jangan melebihi stok
        $variant = $cartItem->productVariant;
        $qty = min($qty, $variant->stock);

        $cartItem->update(['qty' => $qty]);

        return $cartItem->fresh();
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $cartItemId): bool
    {
        $cart = $this->getOrCreateCart();

        return CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->delete() > 0;
    }

    /**
     * Get total item count.
     */
    public function getCount(): int
    {
        $cart = $this->getCart();
        return $cart ? $cart->items->sum('qty') : 0;
    }

    /**
     * Clear entire cart.
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->items()->delete();
        }
    }

    /**
     * Merge guest cart into user cart after login/register.
     * Strategy: guest items are merged in, qty is summed (capped at stock).
     */
    public function mergeGuestCart(string $sessionId, int $userId): void
    {
        $guestCart = Cart::with('items')
            ->where('session_id', $sessionId)
            ->whereNull('user_id')
            ->first();

        if (!$guestCart || $guestCart->items->isEmpty()) {
            // Hapus guest cart kosong
            $guestCart?->delete();
            return;
        }

        // Get or create user cart
        $userCart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['session_id' => null]
        );

        foreach ($guestCart->items as $guestItem) {
            $existingItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            $variant = ProductVariant::find($guestItem->product_variant_id);
            $maxStock = $variant ? $variant->stock : 0;

            if ($existingItem) {
                // Merge: sum qty, capped at stock
                $newQty = min($existingItem->qty + $guestItem->qty, $maxStock);
                $existingItem->update(['qty' => $newQty]);
            } else {
                // Move guest item to user cart
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        // Delete guest cart (remaining items cascade deleted)
        $guestCart->delete();
    }
}
