<?php

namespace App\Livewire\Admin\Settings;

use App\Services\SettingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ShippingSettings extends Component
{
    public $biteshipApiKey = '';
    public $storeOriginPostalCode = '';
    
    public $biteshipCouriers = [];
    
    public $availableCouriers = [
        'jne' => 'JNE',
        'jnt' => 'J&T',
        'sicepat' => 'SiCepat',
        'anteraja' => 'AnterAja',
        'grab' => 'Grab Express',
        'gojek' => 'GoSend',
        'ninja' => 'Ninja Xpress',
        'pos' => 'Pos Indonesia',
    ];

    public function mount(SettingService $settingService)
    {
        $this->biteshipApiKey = $settingService->get('biteship_api_key', '');
        $this->storeOriginPostalCode = $settingService->get('store_origin_postal_code', '');
        $this->biteshipCouriers = $settingService->get('biteship_couriers', ['jne', 'jnt']);
    }

    public function save(SettingService $settingService)
    {
        $this->validate([
            'biteshipApiKey' => 'nullable|string',
            'storeOriginPostalCode' => 'nullable|string|min:5|max:10',
            'biteshipCouriers' => 'array'
        ]);

        $settingService->set('biteship_api_key', $this->biteshipApiKey, 'encrypted');
        $settingService->set('store_origin_postal_code', $this->storeOriginPostalCode);
        $settingService->set('biteship_couriers', $this->biteshipCouriers, 'json');

        $this->dispatch('toast', title: 'Berhasil', message: 'Pengaturan Kurir Biteship berhasil disimpan.', type: 'success');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.settings.shipping-settings');
    }
}
