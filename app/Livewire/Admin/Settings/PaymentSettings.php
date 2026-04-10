<?php

namespace App\Livewire\Admin\Settings;

use App\Services\SettingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PaymentSettings extends Component
{
    public string $xenditSecretKey = '';
    public string $xenditPublicKey = '';
    public string $xenditWebhookToken = '';
    
    // Payment Channels Configuration
    public array $activePaymentChannels = [];
    public array $availableChannels = [
        'CREDIT_CARD' => 'Kartu Kredit / Debit',
        'BCA' => 'BCA Virtual Account',
        'BNI' => 'BNI Virtual Account',
        'BRI' => 'BRI Virtual Account',
        'MANDIRI' => 'Mandiri Virtual Account',
        'PERMATA' => 'Permata Virtual Account',
        'BSI' => 'BSI Virtual Account',
        'ALFAMART' => 'Alfamart',
        'INDOMARET' => 'Indomaret',
        'OVO' => 'OVO',
        'DANA' => 'DANA',
        'SHOPEEPAY' => 'ShopeePay',
        'LINKAJA' => 'LinkAja',
        'QRIS' => 'QRIS',
    ];

    public function mount(SettingService $settingService): void
    {
        // Load existing settings
        $this->xenditSecretKey = $settingService->get('xendit_secret_key', '');
        $this->xenditPublicKey = $settingService->get('xendit_public_key', '');
        $this->xenditWebhookToken = $settingService->get('xendit_webhook_token', '');
        $this->activePaymentChannels = $settingService->get('xendit_payment_channels', array_keys($this->availableChannels));
    }

    public function saveSettings(SettingService $settingService): void
    {
        $this->validate([
            'xenditSecretKey' => 'nullable|string',
            'xenditPublicKey' => 'nullable|string',
            'xenditWebhookToken' => 'nullable|string',
            'activePaymentChannels' => 'array',
        ]);

        // Secret key dan Webhook Token sifatnya sensitif, kita encrypt
        $settingService->set('xendit_secret_key', $this->xenditSecretKey, 'encrypted');
        $settingService->set('xendit_webhook_token', $this->xenditWebhookToken, 'encrypted');
        
        // Public key bisa dibiarkan string biasa atau encrypted (opsional)
        $settingService->set('xendit_public_key', $this->xenditPublicKey, 'encrypted');
        
        // Simpan konfigurasi channel pembayaran (tipe json)
        $settingService->set('xendit_payment_channels', $this->activePaymentChannels, 'json');

        $this->dispatch('toast', title: 'Berhasil', message: 'Pengaturan Xendit berhasil disimpan', type: 'success');
    }

    #[Layout('layouts.admin', ['title' => 'Pengaturan Pembayaran'])]
    public function render()
    {
        return view('livewire.admin.settings.payment-settings');
    }
}
