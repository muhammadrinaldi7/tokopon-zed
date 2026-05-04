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

    protected $rules = [
        'old_phone_brand' => 'required',
        'old_phone_model' => 'required',
        'photos.*' => 'image|max:5120', // Max 5MB per image
    ];

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->to('/login'); // Redirect directly to login path if route name is not standard
        }

        $this->validate();

        $minusDesc = "Kondisi Fisik: " . ($this->old_phone_condition ?? 'Tidak disebutkan') . "\n";
        $minusDesc .= "Kelengkapan: " . (!empty($this->old_phone_sets) ? implode(', ', $this->old_phone_sets) : 'Batangan') . "\n";

        if ($this->old_phone_brand === 'Apple' && $this->old_phone_battery_health) {
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
