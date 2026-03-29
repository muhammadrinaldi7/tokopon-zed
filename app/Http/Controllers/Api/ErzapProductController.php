<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductErzap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ErzapProductController extends Controller
{
    public function store(Request $request)
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
                    ['erzap_id' => $data['kode']],
                    [
                        'name' => $data['nama'] ?? null,
                        'barcode' => $data['barcode'] ?? null,
                        'base_price' => $data['harga_jual'] ?? 0,
                        'discount_price' => $data['harga_diskon'] ?? null,
                        'stock' => $data['available_stok'] ?? 0,
                        'raw_data' => $data // Simpan seluruh data untuk referensi
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
            Log::error('Erzap Sync Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function syncStock(Request $request)
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

                // Pada sync stock cepat, kita hanya update stok dari produk yang sudah ada
                // Atau ciptakan blank produk dengan stok saja (meskipun disarankan produk diimport by API master dulu)
                ProductErzap::updateOrCreate(
                    ['erzap_id' => $data['kode']],
                    [
                        'stock' => $data['stok'] ?? 0,
                    ]
                );
            }
            DB::commit();
            return response()->json(['status' => 'sukses']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erzap Stock Sync Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
