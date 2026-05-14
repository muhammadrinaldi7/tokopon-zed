<?php

namespace App\Livewire\Admin\SellPhone;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SellPhone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public SellPhone $sellPhone;
    
    // Appraisal Form
    public $appraisedValue = 0;
    
    // Convert to Second Product
    public $convertModal = false;
    public $sellPrice = 0;
    public $secondCondition = 'Bekas';
    public $existingProductId = null;

    // Revision
    public $isRevising = false;
    public $revisedAppraisedValue = 0;

    public function mount(SellPhone $sellPhone)
    {
        $this->sellPhone = $sellPhone->load(['user', 'buybackDevice.tier']);
        $this->appraisedValue = $this->sellPhone->appraised_value ?? 0;
    }

    public function submitAppraisal()
    {
        $this->validate([
            'appraisedValue' => 'required|numeric|min:1000'
        ]);

        $this->sellPhone->update([
            'appraised_value' => $this->appraisedValue,
            'status' => 'OFFERED',
        ]);

        $this->dispatch('show-toast', type: 'success', message: 'Penawaran berhasil disimpan dan dikirim ke pengguna.');
    }

    public function submitRevision()
    {
        $this->validate([
            'revisedAppraisedValue' => 'required|numeric|min:1000'
        ]);

        $this->sellPhone->update([
            'appraised_value' => $this->revisedAppraisedValue,
            'status' => 'REVISED_OFFER',
        ]);

        $this->isRevising = false;
        $this->dispatch('show-toast', type: 'success', message: 'Revisi penawaran berhasil dikirim ke pengguna.');
    }

    public function markAsPaid()
    {
        if ($this->sellPhone->status === 'COMPLETED' || $this->sellPhone->status === 'CANCELLED') return;

        $this->sellPhone->update(['status' => 'COMPLETED']);
        
        $this->dispatch('toast', title: 'Lunas', message: 'Status penjualan HP ditandai sebagai Selesai / Lunas.', type: 'success');
    }

    public function reject()
    {
        $this->sellPhone->update(['status' => 'CANCELLED']);
        $this->dispatch('toast', title: 'Ditolak', message: 'Pembelian dibatalkan secara sepihak.', type: 'info');
    }

    public function convertToProduct()
    {
        if ($this->sellPhone->status !== 'COMPLETED') return;

        $this->validate([
            'sellPrice' => 'required|numeric|min:1000',
            'secondCondition' => 'required|string',
        ]);

        DB::transaction(function () {
            $productName = $this->sellPhone->phone_brand . ' ' . $this->sellPhone->phone_model;
            
            $product = null;
            if ($this->existingProductId) {
                $product = \App\Models\Product::find($this->existingProductId);
            } else {
                $product = \App\Models\Product::firstOrCreate(
                    ['name' => $productName, 'is_second' => true],
                    [
                        'slug' => Str::slug($productName . ' Second ' . rand(100, 999)),
                        'brand_id' => null, 
                        'category_id' => \App\Models\Category::first()?->id,
                        'description' => 'Produk unit seken / bekas pakai.',
                        'is_active' => true,
                        'starting_price' => $this->sellPrice,
                    ]
                );
            }

            ProductVariant::create([
                'product_id' => $product->id,
                'sell_phone_id' => $this->sellPhone->id,
                'storage' => $this->sellPhone->phone_storage ?? '-',
                'color' => '-',
                'condition' => $this->secondCondition,
                'price' => $this->sellPrice,
                'stock' => 1,
            ]);
        });

        $this->convertModal = false;
        $this->dispatch('toast', title: 'Berhasil', message: 'Unit HP lama masuk ke Katalog Second.', type: 'success');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.sell-phone.show');
    }
}
