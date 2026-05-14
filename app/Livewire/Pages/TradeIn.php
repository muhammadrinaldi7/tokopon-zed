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

    // Properti HP Lama (Fixed Price)
    public $selected_brand_id;
    public $selected_model_name;
    public $buyback_device_id;

    // For calculation
    public $device_rules = [];
    public $selected_rules = [];
    public $final_price = 0;

    // Fallback notes & UI state
    public $old_phone_additional_note;

    // Temporary properties for UI dropdowns
    public $available_models = [];
    public $available_storages = [];
    public $buyback_device = null;
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

    public function updatedSelectedBrandId()
    {
        $this->selected_model_name = null;
        $this->buyback_device_id = null;
        $this->available_storages = [];
        $this->buyback_device = null;

        if ($this->selected_brand_id) {
            $this->available_models = \App\Models\BuybackDevice::where('brand_id', $this->selected_brand_id)
                ->where('is_active', true)
                ->select('model_name')
                ->distinct()
                ->pluck('model_name')
                ->toArray();
        } else {
            $this->available_models = [];
        }
    }

    public function updatedSelectedModelName()
    {
        $this->buyback_device_id = null;
        $this->buyback_device = null;

        if ($this->selected_brand_id && $this->selected_model_name) {
            $this->available_storages = \App\Models\BuybackDevice::where('brand_id', $this->selected_brand_id)
                ->where('model_name', $this->selected_model_name)
                ->where('is_active', true)
                ->get();
        } else {
            $this->available_storages = [];
        }
    }

    public function updatedBuybackDeviceId()
    {
        if ($this->buyback_device_id) {
            $this->buyback_device = \App\Models\BuybackDevice::with('tier')->find($this->buyback_device_id);
            $this->device_rules = $this->buyback_device ? $this->buyback_device->getFlatRules() : [];
            $this->selected_rules = [];
            $this->calculatePrice();
        } else {
            $this->buyback_device = null;
            $this->device_rules = [];
            $this->final_price = 0;
        }
    }

    public function updatedSelectedRules()
    {
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if (!$this->buyback_device) return;

        $price = $this->buyback_device->base_price;

        $rulesByKey = collect($this->device_rules)->keyBy('key');

        foreach ($this->selected_rules as $ruleKey => $isChecked) {
            if ($isChecked) {
                $rule = $rulesByKey->get($ruleKey);
                if ($rule) {
                    $type = $rule['type'];
                    $val = $rule['value'];

                    if ($type === 'fixed') {
                        $price -= $val;
                    } elseif ($type === 'percentage') {
                        $price -= ($this->buyback_device->base_price * ($val / 100));
                    }
                }
            }
        }

        $this->final_price = max(0, $price); // Ensure price doesn't go below 0
    }

    public function submit()
    {
        // Validasi
        $this->validate([
            'selectedProductId' => 'required',
            'buyback_device_id' => 'required|exists:buyback_devices,id',
            'old_phone_additional_note' => 'nullable|string|max:1000',
            'photos' => 'required|array|min:2',
            'photos.*' => 'image|max:5120',
        ], [
            'required' => 'Bidang ini wajib diisi.',
            'photos.min' => 'Wajib upload minimal 2 foto.',
            'buyback_device_id.required' => 'Perangkat HP Lama wajib dipilih secara lengkap.',
        ]);

        try {
            // --- PROSES DATA ---
            $device = \App\Models\BuybackDevice::with('brand')->find($this->buyback_device_id);

            // Susun teks minus berdasarkan rules yang dicentang
            $checkedRulesNames = [];
            if ($this->buyback_device && !empty($this->device_rules)) {
                $rulesByKey = collect($this->device_rules)->keyBy('key');
                foreach ($this->selected_rules as $ruleKey => $isChecked) {
                    if ($isChecked) {
                        $rule = $rulesByKey->get($ruleKey);
                        if ($rule) {
                            $checkedRulesNames[] = $rule['name'];
                        }
                    }
                }
            }

            $kondisi = !empty($checkedRulesNames) ? implode(', ', $checkedRulesNames) : 'Mulus 100%';
            $catatanText = $this->old_phone_additional_note ? ". Catatan Lain: {$this->old_phone_additional_note}" : "";
            $minusDesc = "Kondisi / Minus: {$kondisi}{$catatanText}";

            // 1. Simpan Model
            $tradeIn = TradeInModel::create([
                'user_id' => Auth::id(),
                'target_product_id' => $this->selectedProductId,
                'buyback_device_id' => $device->id,
                'phone_brand' => $device->brand->name,
                'phone_model' => $device->model_name,
                'phone_ram' => $device->ram,
                'phone_storage' => $device->storage,
                'minus_desc' => $minusDesc,
                'appraised_value' => $this->final_price,
                'status' => 'WAITING_FOR_DEVICE',
            ]);

            // 2. Simpan Foto (Pake looping file temporary)
            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $tradeIn->addMedia($photo->getRealPath())
                        ->usingFileName($photo->getClientOriginalName())
                        ->toMediaCollection('phone_condition');
                }
            }

            session()->flash('message', 'Pengajuan berhasil dikirim!');

            // Cek apakah route ini benar ada di web.php kamu?
            return redirect()->to('/trade-in-history');
        } catch (\Throwable $e) { // Throwable akan menangkap Exception DAN Error fatal
            session()->flash('error', 'Terjadi kesalahan sistem');
        }
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
            'brands' => \App\Models\Brand::whereIn('id', \App\Models\BuybackDevice::where('is_active', true)->select('brand_id')->distinct())->orderBy('name')->get(),
        ]);
    }
}
