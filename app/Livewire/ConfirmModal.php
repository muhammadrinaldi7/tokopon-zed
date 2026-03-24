<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmModal extends Component
{
    public bool $show = false;
    public string $title = '';
    public string $message = '';
    public string $type = 'warning'; // 'warning', 'danger', 'info', 'success'
    public string $confirmText = 'Ya, Lanjutkan';
    public string $cancelText = 'Batal';
    public string $confirmEvent = '';
    public array $confirmParams = [];

    #[On('show-confirm')]
    public function showConfirm(
        string $title,
        string $message,
        string $confirmEvent,
        array $confirmParams = [],
        string $type = 'warning',
        string $confirmText = 'Ya, Lanjutkan',
        string $cancelText = 'Batal',
    ): void {
        $this->title = $title;
        $this->message = $message;
        $this->confirmEvent = $confirmEvent;
        $this->confirmParams = $confirmParams;
        $this->type = $type;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
        $this->show = true;
    }

    #[On('show-alert')]
    public function showAlert(
        string $title,
        string $message,
        string $type = 'info',
        string $confirmText = 'OK',
    ): void {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->confirmText = $confirmText;
        $this->cancelText = '';
        $this->confirmEvent = '';
        $this->confirmParams = [];
        $this->show = true;
    }

    public function confirm(): void
    {
        $this->show = false;

        if ($this->confirmEvent) {
            $this->dispatch($this->confirmEvent, ...$this->confirmParams);
        }

        $this->reset();
    }

    public function cancel(): void
    {
        $this->show = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.confirm-modal');
    }
}
