<?php

namespace App\Livewire\Pages;

use App\Models\TradeIn;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TradeInHistory extends Component
{
    #[Layout('layouts.app', ['title' => 'Riwayat Tukar Tambah'])]
    public function render()
    {
        $tradeIns = TradeIn::with('targetProduct')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.pages.trade-in-history', compact('tradeIns'));
    }
}
