<?php

namespace App\Livewire\Pages;

use App\Models\Brand;
use App\Models\Product;
use App\Models\TradeIn;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitTradeIn extends Component
{
    use WithFileUploads;

    public Product $product;
    public $brands;

    #[Rule('required|string|max:255', as: 'Merek HP')]
    public string $old_phone_brand = '';

    #[Rule('required|string|max:255', as: 'Model HP')]
    public string $old_phone_model = '';

    #[Rule('nullable|string|max:50', as: 'RAM')]
    public string $old_phone_ram = '';

    #[Rule('nullable|string|max:50', as: 'Penyimpanan (Storage)')]
    public string $old_phone_storage = '';

    #[Rule('required|string|max:1000', as: 'Kondisi Minus')]
    public string $old_phone_minus_desc = '';

    #[Rule(['required', 'array', 'min:2', 'max:5'])]
    #[Rule(['photos.*' => 'image|max:5120'], message: ['photos.*.image' => 'File harus berupa gambar.', 'photos.*.max' => 'Ukuran maksimal foto 5MB.'])]
    public array $photos = [];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function submit()
    {
        $this->validate();

        $tradeIn = TradeIn::create([
            'user_id' => Auth::id(),
            'target_product_id' => $this->product->id,
            'old_phone_brand' => $this->old_phone_brand,
            'old_phone_model' => $this->old_phone_model,
            'old_phone_ram' => $this->old_phone_ram,
            'old_phone_storage' => $this->old_phone_storage,
            'old_phone_minus_desc' => $this->old_phone_minus_desc,
            'status' => 'PENDING',
        ]);

        foreach ($this->photos as $photo) {
            $tradeIn->addMedia($photo->getRealPath())
                ->usingName($photo->getClientOriginalName())
                ->toMediaCollection('customer_unit_photos');
        }

        $this->dispatch('toast', title: 'Berhasil Diverifikasi', message: 'Pengajuan Tukar Tambah berhasil dikirim. Tim kami akan segera menaksir harga HP Anda.', type: 'success');

        // Cukup redirect ke beranda atau dashboard untuk saat ini
        return $this->redirect(route('trade-ins.index'), navigate: true);
    }

    #[Layout('layouts.app', ['title' => 'Ajukan Tukar Tambah'])]
    public function render()
    {
        $this->brands = Brand::all();
        return view('livewire.pages.submit-trade-in');
    }
}
