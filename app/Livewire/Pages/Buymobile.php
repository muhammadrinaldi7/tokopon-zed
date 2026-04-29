<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use Livewire\Component;

class Buymobile extends Component
{
    public $selectedBrand = null;

    public $brands = [
        ['name' => 'Apple', 'slug' => 'apple', 'image' => 'iphone.png'],
        ['name' => 'Samsung', 'slug' => 'samsung', 'image' => 'samsung.png'],
        ['name' => 'vivo', 'slug' => 'vivo', 'image' => 'vivo.png'],
        ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'image' => 'xiaomi.png'],
        ['name' => 'Oppo', 'slug' => 'oppo', 'image' => 'oppo.png'],
        ['name' => 'Infinix', 'slug' => 'infinix', 'image' => 'infinix.png'],
        ['name' => 'Realme', 'slug' => 'realme', 'image' => 'realme.png'],
        ['name' => 'Tecno', 'slug' => 'tecno', 'image' => 'tecno.png'],
    ];

    public function setBrand($slug)
    {
        $this->selectedBrand = $slug;
    }

    public function goBack()
    {
        return redirect()->to('/');
    }

    public function render()
    {
        $query = Product::with(['variants', 'brand'])->availableForCustomer();

        // Filter berdasarkan brand jika selectedBrand tidak null
        if ($this->selectedBrand) {
            $query->whereHas('brand', function ($q) {
                $q->where('name', 'like', '%' . $this->selectedBrand . '%');
            });
        }

        $products = $query->get();

        // Mengelompokkan produk berdasarkan nama brand untuk bagian "All Products"
        $groupedProducts = $products->groupBy(function ($item) {
            return $item->brand->name ?? 'Lainnya';
        });

        return view('livewire.pages.buymobile', [
            'products' => $products,
            'groupedProducts' => $groupedProducts
        ]);
    }
}
