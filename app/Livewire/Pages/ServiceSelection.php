<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServiceSelection extends Component
{
    public function navigateToBuyMobile()
{
    return redirect()->route('buy-mobile');
}
    public function render()
    {
        return view('livewire.pages.service-selection');
    }
}
