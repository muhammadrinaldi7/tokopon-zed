<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductErzap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ErzapProductController extends Controller
{
    /**
     * Import products from Erzap (Produk Baru - syihabstore.erzap.com)
     */
    public function store(Request $request)
    {
        return $this->importProducts($request, 'syihab');
    }

    /**
     * Sync stock from Erzap (Produk Baru - syihabstore.erzap.com)
     */
    public function syncStock(Request $request)
    {
        return $this->syncProductStock($request, 'syihab');
    }

    /**
     * Import products from Erzap GSK (Produk Second - gsksyihab.erzap.com)
     */
    public function storeSecond(Request $request)
    {
        return $this->importProducts($request, 'gsksyihab');
    }

    /**
     * Sync stock from Erzap GSK (Produk Second - gsksyihab.erzap.com)
     */
    public function syncStockSecond(Request $request)
    {
        return $this->syncProductStock($request, 'gsksyihab');
    }

    /**
     * Shared import logic with source tagging
     */
    private function importProducts(Request $request, string $source)
    {
        $produks = $request->input('erzap.produks');
        
        if (!$produks) {
            return response()->json(['error' => 'No products found in payload'], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($produks as $data) {
                if (!isset($data['kode'])) {
                    continue;
                }

                ProductErzap::updateOrCreate(
                    ['erzap_id' => $data['kode'], 'source' => $source],
                    [
                        'name' => $data['nama'] ?? null,
                        'barcode' => $data['barcode'] ?? null,
                        'base_price' => $data['harga_jual'] ?? 0,
                        'discount_price' => $data['harga_diskon'] ?? null,
                        'stock' => $data['available_stok'] ?? 0,
                        'raw_data' => $data
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'olzap' => [
                    "notice" => "0",
                    "status" => "1"
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erzap Sync Error ({$source}): " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Shared stock sync logic with source tagging
     */
    private function syncProductStock(Request $request, string $source)
    {
        $produks = $request->input('erzap.produks');
        
        if (!$produks) {
            return response()->json(['error' => 'No products found'], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($produks as $data) {
                if (!isset($data['kode'])) {
                    continue;
                }

                ProductErzap::updateOrCreate(
                    ['erzap_id' => $data['kode'], 'source' => $source],
                    [
                        'stock' => $data['stok'] ?? 0,
                    ]
                );
            }
            DB::commit();
            return response()->json(['status' => 'sukses']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erzap Stock Sync Error ({$source}): " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
