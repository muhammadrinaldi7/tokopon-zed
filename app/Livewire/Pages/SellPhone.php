<?php

namespace App\Livewire\Pages;

use App\Models\Brand; // Pastikan import Model Brand
use Livewire\Component;

class SellPhone extends Component
{
    // Properti yang dibutuhkan agar wire:model di Blade tidak error
    public $old_phone_brand;
    public $old_phone_model;
    public $old_phone_ram;
    public $old_phone_storage;
    public $old_phone_condition;
    public $old_phone_sets = [];
    public $old_phone_additional_note;
    public $old_phone_battery_health;

    public function render()
    {
        return view('livewire.pages.sell-phone', [
            // Kirim data brand ke view
            'brands' => Brand::all()
        ]);
    }
}
