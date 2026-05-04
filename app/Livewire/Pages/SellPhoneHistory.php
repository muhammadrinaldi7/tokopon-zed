<?php

namespace App\Livewire\Pages;

use App\Models\SellPhone;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SellPhoneHistory extends Component
{
    #[Layout('layouts.app', ['title' => 'Riwayat Jual HP'])]
    public function render()
    {
        $sells = SellPhone::where('user_id', Auth::id())
            ->with('media')
            ->latest()
            ->get();
        return view('livewire.pages.sell-phone-history', compact('sells'));
    }
}
