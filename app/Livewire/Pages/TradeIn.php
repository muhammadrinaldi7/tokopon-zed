<?php

namespace App\Livewire\Pages;

use App\Models\Brand;
use App\Models\Product;
use App\Models\TradeIn as TradeInModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class TradeIn extends Component
{
    use WithFileUploads;

    public $selectedProductId;
    public $selectedTargetBrand = null;

    // Properti Form
    public $old_phone_brand;
    public $old_phone_model;
    public $old_phone_ram;
    public $old_phone_storage;

    // Properti yang akan digabung nanti
    public $old_phone_condition; // Radio
    public $old_phone_sets = []; // Checkbox
    public $old_phone_additional_note; // Catatan manual tambahan
    public $old_phone_battery_health;
    public $photos = [];

    /**
     * Fungsi mount dijalankan sekali saat halaman pertama kali dibuka.
     * Kita masukkan parameter $product (diambil dari URL {product?})
     */
    public function mount(Product $product = null)
    {
        if ($product && $product->exists) {
            $this->selectedProductId = $product->id;

            // Tambahkan baris ini untuk otomatis mengisi brand incaran
            // Kita ambil nama brand dari relasi product->brand
            if ($product->brand) {
                $this->selectedTargetBrand = $product->brand->name;
            }
        }
    }

    public function submit()
    {
        $this->validate([
            'selectedProductId' => 'required',
            'old_phone_brand' => 'required',
            'old_phone_model' => 'required',
            'old_phone_condition' => 'required',
            'old_phone_battery_health' => $this->old_phone_brand === 'Apple' ? 'required|integer|min:1|max:100' : 'nullable',
            'photos' => 'nullable|array', // Tambahkan validasi foto jika perlu
        ]);

        // --- PROSES PENGGABUNGAN DATA ---
        $kelengkapan = implode(', ', $this->old_phone_sets);
        $bhText = ($this->old_phone_brand === 'Apple' && $this->old_phone_battery_health)
            ? "Battery Health: {$this->old_phone_battery_health}%. "
            : "";

        $deskripsiLengkap = "Kondisi: " . $this->old_phone_condition . ". ";
        $deskripsiLengkap .= $bhText;
        $deskripsiLengkap .= "Kelengkapan: " . ($kelengkapan ?: 'Tidak ada') . ". ";
        $deskripsiLengkap .= "Catatan: " . ($this->old_phone_additional_note ?: '-');

        $tradeIn = TradeInModel::create([
            'user_id' => Auth::id(),
            'target_product_id' => $this->selectedProductId,
            'old_phone_brand' => $this->old_phone_brand,
            'old_phone_model' => $this->old_phone_model,
            'old_phone_ram' => $this->old_phone_ram,
            'old_phone_storage' => $this->old_phone_storage,
            'old_phone_minus_desc' => $deskripsiLengkap,
            'status' => 'PENDING',
        ]);

        // Opsional: Proses simpan foto di sini jika kamu sudah setup storage/media library

        session()->flash('message', 'Pengajuan berhasil dikirim!');

        // Gunakan redirect()->route() agar lebih aman
        return redirect()->to('trade-in-history');
    }
    public function updatedSelectedTargetBrand()
    {
        // Reset pilihan produk saat user mengganti brand
        $this->selectedProductId = null;
    }
    public function render()
    {
        $targetProducts = Product::query();

        if ($this->selectedTargetBrand) {
            $targetProducts->whereHas('brand', function ($q) {
                $q->where('name', $this->selectedTargetBrand);
            });
        } elseif ($this->selectedProductId) {
            // Fallback: Jika ada product ID tapi brand belum terpilih (misal saat inisiasi)
            // Tetap tampilkan setidaknya produk yang dipilih tersebut
            $targetProducts->where('id', $this->selectedProductId);
        } else {
            $targetProducts->whereRaw('1 = 0');
        }

        return view('livewire.pages.trade-in', [
            'products' => $targetProducts->get(),
            'brands' => Brand::all(),
        ]);
    }
}
