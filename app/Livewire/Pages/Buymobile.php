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
    public array $products = [];

    public function mount()
    {
        // Isi data hanya sekali saat komponen pertama kali dimuat
        $this->products = [
            [
                'name' => 'iPhone 17 Pro',
                'brand' => 'iPhone',
                'status' => 'Used',
                'price' => 1299,
                'image' => 'assets/img/iphone17.png',
            ],
            [
                'name' => 'Samsung Galaxy S25',
                'brand' => 'samsung',
                'status' => 'New',
                'price' => 899,
                'image' => 'assets/img/samsungs25.png',
            ],
            [
                'name' => 'Samsung Galaxy S25 Ultra',
                'brand' => 'samsung',
                'status' => 'Used',
                'price' => 599,
                'image' => 'assets/img/samsungs25ulta.png',
            ],
            [
                'name' => 'iPhone 14 Plus',
                'brand' => 'iPhone',
                'status' => 'Used',
                'price' => 649,
                'image' => 'assets/img/iphone14.png',
            ],
            [
                'name' => 'iPhone 15',
                'status' => 'New',
                'price' => 499,
                'image' => 'assets/img/iphone15.png',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.pages.buymobile');
    }
}
