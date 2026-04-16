<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Buymobile extends Component
{
    public $brands = [
        ['name' => 'iPhone', 'slug' => 'iphone', 'image' => 'iphone.png'],
        ['name' => 'Samsung', 'slug' => 'samsung', 'image' => 'samsung.png'],
        ['name' => 'vivo', 'slug' => 'vivo', 'image' => 'vivo.png'],
        ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'image' => 'xiaomi.png'],
        ['name' => 'Oppo', 'slug' => 'oppo', 'image' => 'oppo.png'],
        ['name' => 'Infinix', 'slug' => 'infinix', 'image' => 'infinix.png'],
        ['name' => 'Realme', 'slug' => 'realme', 'image' => 'realme.png'],
        ['name' => 'Tecno', 'slug' => 'tecno', 'image' => 'tecno.png'],
    ];
    public function render()
    {
        $products = \App\Models\Product::with(['variants'])->availableForCustomer()->get();
        return view('livewire.pages.buymobile', ['products' => $products]);
    }
}
