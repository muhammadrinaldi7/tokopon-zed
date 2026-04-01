<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Catalog extends Component
{
    // Definisikan sebagai properti class
    public array $products = [];

    public function mount()
    {
        // Isi data hanya sekali saat komponen pertama kali dimuat
        $this->products = [
            [
                'name' => 'iPhone 17 Pro',
                'status' => 'Used',
                'price' => 1299,
                'image' => 'assets/img/iphone17.png',
            ],
            [
                'name' => 'Samsung Galaxy S25',
                'status' => 'New',
                'price' => 899,
                'image' => 'assets/img/samsungs25.png',
            ],
            [
                'name' => 'Samsung Galaxy S25 Ultra',
                'status' => 'Used',
                'price' => 599,
                'image' => 'assets/img/samsungs25ulta.png',
            ],
            [
                'name' => 'iPhone 14 Plus',
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
        return view('livewire.pages.catalog');
    }
}