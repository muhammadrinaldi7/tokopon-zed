<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServiceSelection extends Component
{
    public function navigateToBuyMobile()
    {
        return redirect()->route('buy-mobile');
    }
    public function navigateToRepair()
    {
        return redirect()->route('phone-repair');
    }
    public function navigateToTradeIn()
    {
        return redirect()->route('trade-in');
    }
    public function navigateToSellPhone()
    {
        return redirect()->route('sell-phone');
    }
    public function render()
    {
        return view('livewire.pages.service-selection');
    }
}
