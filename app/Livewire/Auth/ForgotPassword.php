<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Lupa Password - TokoPun')]
class ForgotPassword extends Component
{
    public string $email = '';
    public bool $linkSent = false;

    protected array $rules = [
        'email' => 'required|email',
    ];

    protected array $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ];

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->linkSent = true;
            $this->reset('email');
            session()->flash('status', 'Link reset password telah dikirim ke email Anda.');
        } else {
            $this->addError('email', 'Email tidak ditemukan di sistem kami.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
