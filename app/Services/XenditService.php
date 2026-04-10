<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;

class XenditService
{
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
        $this->initialize();
    }

    /**
     * Set up Xendit Configuration based on settings.
     */
    private function initialize(): void
    {
        $secretKey = $this->settingService->get('xendit_secret_key');
        if (!empty($secretKey)) {
            Configuration::setXenditKey($secretKey);
        }
    }

    /**
     * Check if Xendit is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->settingService->get('xendit_secret_key'));
    }

    /**
     * Create an invoice from an Order
     */
    public function createInvoice(Order $order): ?array
    {
        if (!$this->isConfigured()) {
            throw new Exception("Xendit API Key is not configured.");
        }

        $apiInstance = new InvoiceApi();
        
        $customer = $order->user;
        $items = [];
        
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->variant->product->name . ' - ' . $item->variant->condition,
                'quantity' => (int) $item->qty,
                'price' => (float) $item->price_at_checkout,
            ];
        }

        // Ambil payment channels yang diatur di admin
        $paymentMethods = $this->settingService->get('xendit_payment_channels', []);

        $request = new CreateInvoiceRequest([
            'external_id' => $order->order_number,
            'amount' => (float) $order->grand_total,
            'description' => 'Pembayaran Pesanan ' . $order->order_number . ' via TokoPun',
            'invoice_duration' => 86400, // 24 jam
            'customer' => [
                'given_names' => $customer->name ?? 'Guest',
                'email' => $customer->email ?? '',
                'mobile_number' => $order->shipping_address_snapshot['phone_number'] ?? '',
            ],
            'success_redirect_url' => route('orders.confirmation', $order->id),
            'failure_redirect_url' => route('orders.show', $order->id),
            'currency' => 'IDR',
            'items' => $items,
            'payment_methods' => !empty($paymentMethods) ? $paymentMethods : null,
        ]);

        try {
            $result = $apiInstance->createInvoice($request);
            // Result is an object, convert to array for easier handling
            return json_decode(json_encode($result), true);
        } catch (\Xendit\XenditSdkException $e) {
            throw new Exception("Xendit Error: " . $e->getMessage());
        }
    }
}
