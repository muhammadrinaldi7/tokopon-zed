<?php

namespace App\Livewire\Admin\Buyback;

use App\Models\Brand;
use App\Models\BuybackDevice;
use App\Models\BuybackTier;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['title' => 'Tambah Perangkat Buyback'])]
class DeviceForm extends Component
{
    // Data HP
    public $brand_id;
    public $model_name;
    public $ram;
    public $storage;
    public $base_price;
    public $is_active = true;

    // Tier yang ter-detect dari base_price (read-only preview)
    public ?int $detected_tier_id    = null;
    public string $detected_tier_name = '';

    // ──────────────────────────────────────────────
    // Auto-detect tier saat base_price berubah
    // ──────────────────────────────────────────────

    public function updatedBasePrice($value)
    {
        $this->detected_tier_id   = null;
        $this->detected_tier_name = '';

        if (is_numeric($value) && $value > 0) {
            $tier = BuybackTier::findByPrice((float) $value);
            if ($tier) {
                $this->detected_tier_id   = $tier->id;
                $this->detected_tier_name = $tier->name;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'brand_id'   => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'storage'    => 'required|string',
            'base_price' => 'required|numeric|min:0',
        ]);

        // Cari tier yang sesuai dengan harga
        $tier = BuybackTier::findByPrice((float) $this->base_price);

        $device = BuybackDevice::create([
            'brand_id'        => $this->brand_id,
            'buyback_tier_id' => $tier?->id,
            'model_name'      => $this->model_name,
            'ram'             => $this->ram,
            'storage'         => $this->storage,
            'base_price'      => $this->base_price,
            'is_active'       => $this->is_active,
        ]);

        $tierMsg = $tier
            ? "Tier \"<strong>{$tier->name}</strong>\" berhasil di-assign otomatis."
            : 'Tidak ada tier yang cocok dengan harga ini. Harap cek konfigurasi tier.';

        $this->dispatch('toast',
            title:   'Perangkat Tersimpan',
            message: $tierMsg,
            type:    $tier ? 'success' : 'warning'
        );

        return $this->redirect(route('admin.buyback.index'), navigate: true);
    }

    public function render()
    {
        $detectedTier = $this->detected_tier_id
            ? BuybackTier::find($this->detected_tier_id)
            : null;

        return view('livewire.admin.buyback.device-form', [
            'brands'       => Brand::orderBy('name')->get(),
            'allTiers'     => BuybackTier::orderBy('min_price')->get(),
            'detectedTier' => $detectedTier,
        ]);
    }
}
