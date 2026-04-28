<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Catalog extends Component
{
    public $products;

    public function mount()
    {
        $this->products = \App\Models\Product::with(['variants'])->availableForCustomer()->get();
    }

    public function render()
    {
        return view('livewire.pages.catalog');
    }
}
