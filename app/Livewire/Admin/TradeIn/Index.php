<?php

namespace App\Livewire\Admin\TradeIn;

use App\Models\TradeIn;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $query = TradeIn::with(['user', 'targetProduct'])->latest();

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhere('old_phone_model', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.trade-in.index', [
            'tradeIns' => $query->paginate(10)
        ]);
    }
}
