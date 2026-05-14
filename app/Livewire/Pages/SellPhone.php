<?php

namespace App\Livewire\Pages;

use App\Models\Brand;
use App\Models\SellPhone as SellPhoneModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

class SellPhone extends Component
{
    use WithFileUploads;

    public $selected_brand_id;
    public $selected_model_name;
    public $buyback_device_id;

    // For calculation
    public $device_rules = [];
    public $selected_rules = [];
    public $final_price = 0;

    // Fallback notes
    public $old_phone_additional_note;
    public $photos = [];

    // Temporary properties for UI dropdowns
    public $available_models = [];
    public $available_storages = [];
    public $buyback_device = null;

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
        // dd($this->selected_rules);
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if (!$this->buyback_device) return;

        $price = $this->buyback_device->base_price;

        // Convert flat rules array to key-based collection for easy lookup
        $rulesByKey = collect($this->device_rules)->keyBy('key');

        foreach ($this->selected_rules as $key => $value) {
            $ruleId = null;
            if (is_bool($value) && $value) {
                // Checkbox checked
                $ruleId = $key;
            } elseif (is_string($value) && !empty($value)) {
                // Radio button selected
                $ruleId = $value;
            }

            if ($ruleId) {
                $rule = $rulesByKey->get($ruleId);

                if ($rule) {
                    $type = $rule['type'];
                    $val = $rule['value'];

                    // Hitung nominal perubahan (fixed atau persentase)
                    $adjustment = ($type == 'fixed')
                        ? $val
                        : ($this->buyback_device->base_price * ($val / 100));

                    // CEK DISINI: Jika key mengandung kata 'kelengkapan', maka ditambah (+)
                    // Selain itu (seperti layar/fisik), maka dikurangi (-)
                    if (str_contains($ruleId, 'kelengkapan')) {
                        $price += $adjustment;
                    } else {
                        $price -= $adjustment;
                    }
                }
            }
        }

        $this->final_price = max(0, $price); // Pastikan harga tidak minus
    }

    protected function rules()
    {
        return [
            'buyback_device_id'         => 'required|exists:buyback_devices,id',
            'selected_rules'            => 'required|array|min:1',
            'photos'                    => 'required|array|min:1|max:5',
            'photos.*'                  => 'image|max:5120',
            'old_phone_additional_note' => 'nullable|string|max:1000',
            // 'old_phone_battery_health'  => 'required_if:buyback_device->brand->name,Apple,APPLE|nullable|numeric|min:1|max:100',
            // Jika kamu masih memakai BH atau RAM secara manual, tambahkan di sini. 
            // Tapi jika sudah include di selected_rules, ini sudah cukup.
        ];
    }

    protected $messages = [
        'buyback_device_id.required'    => 'Silakan pilih model dan kapasitas penyimpanan terlebih dahulu.',
        'buyback_device_id.exists'      => 'Perangkat tidak ditemukan.',
        'selected_rules.required'       => 'Silakan pilih kondisi perangkat Anda.',
        'selected_rules.min'            => 'Setidaknya satu kondisi harus dipilih.',
        'photos.required'               => 'Wajib mengunggah minimal 1 foto HP.',
        'photos.min'                    => 'Wajib mengunggah minimal 1 foto HP.',
        'photos.max'                    => 'Maksimal hanya boleh mengunggah 5 foto.',
        'photos.*.image'                => 'File harus berupa gambar.',
        'photos.*.max'                  => 'Ukuran foto maksimal 5MB per file.',
        'old_phone_additional_note.max' => 'Catatan tambahan maksimal 1000 karakter.',
    ];

    public function submit()
    {
        // Cek Autentikasi
        if (!Auth::check()) {
            return redirect()->to('/login');
        }

        // Jalankan Validasi
        $this->validate();

        // 1. Ambil data device terbaru dari database
        $device = \App\Models\BuybackDevice::with('brand')->find($this->buyback_device_id);

        if (!$device) {
            $this->dispatch('show-toast', type: 'error', message: 'Data perangkat tidak valid.');
            return;
        }

        // 2. Identifikasi semua kondisi/minus yang dipilih (Checkbox & Radio)
        $checkedRulesNames = [];
        $rulesByKey = collect($this->device_rules)->keyBy('key');

        foreach ($this->selected_rules as $key => $value) {
            $ruleId = null;

            // Logic pendeteksian key (sesuai yang kita bahas tadi)
            if (is_bool($value) && $value) {
                $ruleId = $key; // Checkbox (Kelengkapan)
            } elseif (is_string($value) && !empty($value)) {
                $ruleId = $value; // Radio (Layar/Fisik)
            }

            if ($ruleId) {
                $rule = $rulesByKey->get($ruleId);
                if ($rule) {
                    $checkedRulesNames[] = $rule['name'];
                }
            }
        }

        // 3. Susun Deskripsi Minus
        $kondisi = !empty($checkedRulesNames) ? implode(', ', $checkedRulesNames) : 'Mulus / Normal';
        $catatanText = $this->old_phone_additional_note ? ". Catatan Tambahan: {$this->old_phone_additional_note}" : "";
        $minusDesc = "Kondisi: {$kondisi}{$catatanText}";

        // 4. Simpan ke Database
        $sellPhone = \App\Models\SellPhone::create([
            'user_id'           => Auth::id(),
            'buyback_device_id' => $device->id,
            'phone_brand'       => $device->brand->name,
            'phone_model'       => $device->model_name,
            'phone_ram'         => $device->ram,
            'phone_storage'     => $device->storage,
            'minus_desc'        => $minusDesc,
            'appraised_value'   => $this->final_price,
            'status'            => 'WAITING_FOR_DEVICE',
        ]);

        // 5. Upload Media (Spatie Media Library)
        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $sellPhone->addMedia($photo->getRealPath())
                    ->usingFileName($photo->getClientOriginalName())
                    ->toMediaCollection('photos');
            }
        }

        $this->dispatch('show-toast', type: 'success', message: 'Penawaran disetujui! Silakan kirim perangkat Anda.');

        // 6. Reset form ke keadaan semula
        $this->reset([
            'selected_brand_id',
            'selected_model_name',
            'buyback_device_id',
            'selected_rules',
            'final_price',
            'old_phone_additional_note',
            'photos',
            'available_models',
            'available_storages',
            'buyback_device'
        ]);

        return $this->redirect(route('sell-phone-history'), navigate: true);
    }

    #[Layout('layouts.app', ['title' => 'Sell Mobile Phone'])]
    public function render()
    {
        $brands = \App\Models\Brand::whereIn('id', \App\Models\BuybackDevice::where('is_active', true)->select('brand_id')->distinct())->orderBy('name')->get();
        return view('livewire.pages.sell-phone', [
            'brands' => $brands,
        ]);
    }
}
