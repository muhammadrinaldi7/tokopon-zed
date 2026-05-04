<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ToastNotification extends Component
{
    public array $toasts = [];

    #[On('show-toast')]
    public function addToast($type, $message)
    {
        // Buat ID unik untuk setiap toast
        $id = uniqid();

        $this->toasts[$id] = [
            'id' => $id,
            'type' => $type,
            'message' => $message,
        ];

        // Eksekusi Vanilla JS dari backend untuk auto-close setelah 3.5 detik
        // $wire adalah objek global Livewire v3 untuk komponen ini
        $this->js("setTimeout(() => \$wire.removeToast('{$id}'), 3500);");
    }

    public function removeToast($id)
    {
        // Hapus toast dari array berdasarkan ID
        if (isset($this->toasts[$id])) {
            unset($this->toasts[$id]);
        }
    }
    public function render()
    {
        return view('livewire.toast-notification');
    }
}
