<?php

namespace App\Livewire\Admin\SellPhone;

use App\Models\SellPhone;
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
        $query = SellPhone::with(['user'])->latest();

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhere('phone_model', 'like', '%' . $this->search . '%')
              ->orWhere('phone_brand', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.sell-phone.index', [
            'sellPhones' => $query->paginate(10)
        ]);
    }
}
