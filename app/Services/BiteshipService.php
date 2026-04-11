<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected $apiKey;
    protected $baseUrl;
    protected $originPostalCode;

    public function __construct(SettingService $settingService)
    {
        $this->apiKey = $settingService->get('biteship_api_key', '');
        $this->originPostalCode = $settingService->get('store_origin_postal_code', '');
        $this->baseUrl = 'https://api.biteship.com/v1';
    }

    /**
     * Get Shipping Rates from Biteship
     *
     * @param string $destinationPostalCode
     * @param array $couriers Array of courier codes e.g. ['jne', 'sicepat']
     * @param array $items Array of cart items mapped for Biteship
     * @return array|null
     */
    public function getRates($destinationPostalCode, $couriers = null, $items = [])
    {
        if (empty($this->apiKey) || empty($this->originPostalCode)) {
            Log::warning('Biteship configuration missing.');
            return null;
        }

        // Default couriers from settings if not passed
        if (empty($couriers)) {
            $settingService = app(SettingService::class);
            $couriers = $settingService->get('biteship_couriers', ['jne', 'jnt']);
        }
        
        $couriersStr = implode(',', $couriers);

        // Map items if structure is different
        // Biteship requires weight in grams minimum 1
        $mappedItems = [];
        foreach ($items as $item) {
            $mappedItems[] = [
                'name' => mb_substr($item['name'] ?? 'Barang', 0, 50),
                'value' => (int) ($item['price'] ?? 1000),
                'quantity' => (int) ($item['qty'] ?? 1),
                'weight' => 1000, // Hardcoded to 1kg per item for now since TokoPun DB has no weight column yet
            ];
        }

        if (empty($mappedItems)) {
            // At least one dummy item to get range
            $mappedItems[] = [
                'name' => 'Produk',
                'value' => 100000,
                'quantity' => 1,
                'weight' => 1000
            ];
        }

        $payload = [
            'origin_postal_code' => $this->originPostalCode,
            'destination_postal_code' => $destinationPostalCode,
            'couriers' => $couriersStr,
            'items' => $mappedItems,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "{$this->apiKey}",
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/rates/couriers", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Biteship API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Biteship Exception: ' . $e->getMessage());
            return null;
        }
    }
}
