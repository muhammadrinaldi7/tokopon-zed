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
        foreach ($this->selected_rules as $ruleKey => $isChecked) {
            // dd($ruleKey, $isChecked);
            if ($isChecked) {
                $rule = $rulesByKey->get($ruleKey);
                // dd($rule);
                if ($rule) {
                    // dd($rule);
                    $type = $rule['type'];
                    $val = $rule['value'];

                    if ($type == 'fixed') {
                        $price -= $val;
                    } else {
                        $price -= ($this->buyback_device->base_price * ($val / 100));
                    }
                }
            }
        }

        $this->final_price = max(0, $price); // Ensure price doesn't go below 0
    }

    protected function rules()
    {
        return [
            'old_phone_brand'           => 'required|string',
            'old_phone_model'           => 'required|string|max:255',
            // Wajib diisi KECUALI brand adalah Apple/APPLE
            'old_phone_ram'             => 'required_unless:old_phone_brand,Apple,APPLE|nullable|string',
            'old_phone_storage'         => 'required|string',
            'old_phone_condition'       => 'required|string',
            'old_phone_sets'            => 'required|array',
            'old_phone_additional_note' => 'nullable|string|max:1000',

            // Wajib diisi JIKA brand adalah Apple/APPLE, dan harus berupa angka 1-100
            'old_phone_battery_health'  => 'required_if:old_phone_brand,Apple,APPLE|nullable|numeric|min:1|max:100',

            // Validasi file foto (wajib ada minimal 1, maksimal 5 file)
            'photos'                    => 'required|array|min:1|max:5',
            'photos.*'                  => 'image|max:5120', // Maks 5MB per gambar
        ];
    }

    // Custom pesan error bahasa Indonesia
    protected $messages = [
        'old_phone_brand.required'          => 'Merk HP wajib dipilih.',
        'old_phone_model.required'          => 'Model/Seri HP wajib diisi.',
        // Ubah pesan error untuk RAM menggunakan .required_unless
        'old_phone_ram.required_unless'     => 'Kapasitas RAM wajib dipilih untuk perangkat Android.',
        'old_phone_storage.required'        => 'Kapasitas Storage wajib dipilih.',
        'old_phone_condition.required'      => 'Kondisi fisik wajib dipilih.',
        'old_phone_sets.required'           => 'Kelengkapan wajib diisi.',
        'old_phone_battery_health.required_if' => 'Kesehatan Baterai (BH) wajib diisi untuk perangkat Apple.',
        'old_phone_battery_health.min'      => 'Kesehatan Baterai minimal 1%.',
        'old_phone_battery_health.max'      => 'Kesehatan Baterai maksimal 100%.',
        'photos.required'                   => 'Wajib mengunggah minimal 1 foto HP.',
        'photos.max'                        => 'Maksimal hanya boleh mengunggah 5 foto.',
        'photos.*.image'                    => 'File harus berupa gambar.',
        'photos.*.max'                      => 'Ukuran foto maksimal 5MB per file.',
    ];

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->to('/login'); // Redirect directly to login path if route name is not standard
        }

        $this->validate();

        // 1. Dapatkan device dari DB untuk info base
        $device = \App\Models\BuybackDevice::with('brand')->find($this->buyback_device_id);

        // 2. Susun teks minus berdasarkan rules yang dicentang
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

        $sellPhone = SellPhoneModel::create([
            'user_id' => Auth::id(),
            'buyback_device_id' => $device->id,
            'phone_brand' => $device->brand->name,
            'phone_model' => $device->model_name,
            'phone_ram' => $device->ram,
            'phone_storage' => $device->storage,
            'minus_desc' => $minusDesc,
            'appraised_value' => $this->final_price,
            'status' => 'WAITING_FOR_DEVICE', // Langsung setuju karena harga sudah fixed
        ]);

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $sellPhone->addMedia($photo)->toMediaCollection('photos');
            }
        }

        $this->dispatch('show-toast', type: 'success', message: 'Penawaran disetujui! Silakan kirim perangkat Anda ke toko kami.');

        // Reset form
        $this->reset([
            'selected_brand_id',
            'selected_model_name',
            'buyback_device_id',
            'selected_rules',
            'final_price',
            'old_phone_additional_note',
            'photos'
        ]);

        return $this->redirect(route('sell-phone-history'));
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
