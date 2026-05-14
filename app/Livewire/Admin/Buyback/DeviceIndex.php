<?php

namespace App\Livewire\Admin\Buyback;

use App\Models\BuybackDevice;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['title' => 'Daftar Perangkat Buyback'])]
class DeviceIndex extends Component
{
    public function render()
    {
        $devices = BuybackDevice::with(['brand', 'tier'])
            ->orderBy('brand_id')
            ->orderBy('model_name')
            ->get();

        return view('livewire.admin.buyback.device-index', compact('devices'));
    }
}
