<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    #[On('cart-updated')]
    public function refreshCount(): void
    {
        $this->count = app(CartService::class)->getCount();
    }

    public function render()
    {
        return <<<'HTML'
        <span>{{ $count }}</span>
        HTML;
    }
}
