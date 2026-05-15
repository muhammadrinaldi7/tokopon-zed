<?php

namespace App\Livewire\Admin\Settings\Warehouse;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\AccurateService;

#[Layout('layouts.admin')]
class Index extends Component
{
    public $warehouse = [];
    public function getWarehouse()
    {
        try {
            $warehouse = (new AccurateService())->getWarehouseList();
            return $warehouse;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return [];
        }
    }

    public function mount()
    {
        $this->warehouse = $this->getWarehouse();
    }
    public function render()
    {
        // dd($this->warehouse);
        return view('livewire.admin.settings.warehouse.index');
    }
}
