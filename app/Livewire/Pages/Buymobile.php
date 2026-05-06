<?php

namespace App\Livewire\Pages;

use App\Models\Brand; // Pastikan Model Brand di-import
use App\Models\Product;
use Livewire\Component;

class Buymobile extends Component
{
    public $selectedBrand = null;

    public function setBrand($name)
    {
        $this->selectedBrand = $name;
    }

    public function goBack()
    {
        return redirect()->to('/');
    }

    public function render()
    {
        // 1. Ambil data brands dari database (urutkan sesuai kebutuhan, misal by nama atau ID)
        $brands = Brand::orderBy('id', 'asc')->get();

        $query = Product::with(['variants', 'brand'])->availableForCustomer();

        // 2. Filter berdasarkan brand jika selectedBrand tidak null
        if ($this->selectedBrand) {
            $query->whereHas('brand', function ($q) {
                // Menggunakan exact match "=" lebih aman dibanding "like"
                $q->where('name', $this->selectedBrand);
            });
        }

        $products = $query->get();

        // 3. Mengelompokkan produk berdasarkan nama brand
        $groupedProducts = $products->groupBy(function ($item) {
            return $item->brand->name ?? 'Lainnya';
        });

        // 4. Kirim $brands ke view
        return view('livewire.pages.buymobile', [
            'brands' => $brands,
            'products' => $products,
            'groupedProducts' => $groupedProducts
        ]);
    }
}
