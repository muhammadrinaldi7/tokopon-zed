<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;
    public bool $showVariantPicker = false;
    public ?int $selectedVariantId = null;
    public bool $added = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function openVariantPicker(): void
    {
        $this->product->load(['variants' => function ($q) {
            $q->where('stock', '>', 0);
        }]);

        // Jika hanya 1 varian dengan stok, langsung add
        $availableVariants = $this->product->variants->where('stock', '>', 0);

        if ($availableVariants->count() === 1) {
            $this->addToCart($availableVariants->first()->id);
            return;
        }

        if ($availableVariants->count() === 0) {
            return;
        }

        $this->showVariantPicker = true;
    }

    public function addToCart(?int $variantId = null): void
    {
        $id = $variantId ?? $this->selectedVariantId;
        if (!$id) return;

        $cartService = app(CartService::class);
        $cartService->addItem($id, 1);

        $this->showVariantPicker = false;
        $this->added = true;

        $this->dispatch('cart-updated');

        // Reset "added" state after 2 seconds via JS
    }

    public function closeVariantPicker(): void
    {
        $this->showVariantPicker = false;
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
