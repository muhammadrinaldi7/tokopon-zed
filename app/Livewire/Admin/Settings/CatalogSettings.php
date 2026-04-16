<?php

namespace App\Livewire\Admin\Settings;

use App\Services\SettingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CatalogSettings extends Component
{
    public $minimumStockThreshold = 5;

    public function mount(SettingService $settingService)
    {
        $this->minimumStockThreshold = $settingService->get('minimum_stock_threshold', 5);
    }

    public function save(SettingService $settingService)
    {
        $this->validate([
            'minimumStockThreshold' => 'required|integer|min:0'
        ]);

        $settingService->set('minimum_stock_threshold', $this->minimumStockThreshold);

        $this->dispatch('toast', title: 'Berhasil', message: 'Pengaturan Katalog berhasil disimpan.', type: 'success');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.settings.catalog-settings');
    }
}
