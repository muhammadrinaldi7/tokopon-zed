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
    public $old_phone_brand;
    public $old_phone_model;
    public $old_phone_ram;
    public $old_phone_storage;
    public $old_phone_condition;
    public $old_phone_sets = [];
    public $old_phone_additional_note;
    public $old_phone_battery_health;
    public $photos = [];

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

        $minusDesc = "Kondisi Fisik: " . ($this->old_phone_condition ?? 'Tidak disebutkan') . "\n";
        $minusDesc .= "Kelengkapan: " . (!empty($this->old_phone_sets) ? implode(', ', $this->old_phone_sets) : 'Batangan') . "\n";

        // Pengecekan case-insensitive untuk Apple
        if (strtolower($this->old_phone_brand) === 'apple' && $this->old_phone_battery_health) {
            $minusDesc .= "Battery Health: " . $this->old_phone_battery_health . "%\n";
        }

        if ($this->old_phone_additional_note) {
            $minusDesc .= "Catatan: " . $this->old_phone_additional_note;
        }

        $sellPhone = SellPhoneModel::create([
            'user_id' => Auth::id(),
            'phone_brand' => $this->old_phone_brand,
            'phone_model' => $this->old_phone_model,
            'phone_ram' => $this->old_phone_ram,
            'phone_storage' => $this->old_phone_storage,
            'minus_desc' => $minusDesc,
            'status' => 'PENDING',
        ]);

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $sellPhone->addMedia($photo)->toMediaCollection('photos');
            }
        }

        $this->dispatch('show-toast', type: 'success', message: 'Penawaran HP Anda berhasil dikirim! Tim kami akan segera meninjau.');

        // Reset form
        $this->reset([
            'old_phone_brand',
            'old_phone_model',
            'old_phone_ram',
            'old_phone_storage',
            'old_phone_condition',
            'old_phone_sets',
            'old_phone_additional_note',
            'old_phone_battery_health',
            'photos'
        ]);

        return $this->redirect(route('sell-phone-history'));
    }

    #[Layout('layouts.app', ['title' => 'Sell Mobile Phone'])]
    public function render()
    {
        return view('livewire.pages.sell-phone', [
            'brands' => Brand::orderBy('name')->get()
        ]);
    }
}
