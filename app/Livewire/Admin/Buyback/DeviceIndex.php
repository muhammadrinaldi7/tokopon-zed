<?php

namespace App\Livewire\Admin\Buyback;

use App\Models\BuybackDevice;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['title' => 'Daftar Perangkat Buyback'])]
class DeviceIndex extends Component
{
    public function syncTierDevice()
    {
        $devices = BuybackDevice::whereNotNull('base_price')->get();

        $count = 0;
        foreach ($devices as $device) {
            $device->assignTierByPrice();
            $count++;
        }

        $this->dispatch(
            'toast',
            title: 'Berhasil Disinkronisasi',
            message: "Berhasil meng-assign tier untuk {$count} perangkat berdasarkan harganya.",
            type: 'success'
        );
    }
    public function render()
    {
        $devices = BuybackDevice::with(['brand', 'tier'])
            ->orderBy('brand_id')
            ->orderBy('model_name')
            ->get();

        return view('livewire.admin.buyback.device-index', compact('devices'));
    }
}
