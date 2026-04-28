<?php

namespace App\Observers;

use App\Models\ProductErzap;
use App\Models\ProductVariant;
use App\Models\Product;

class ProductErzapObserver
{
    /**
     * Handle the ProductErzap "saved" event (covers created and updated).
     */
    public function saved(ProductErzap $productErzap): void
    {
        $this->syncToVariants($productErzap);
    }

    /**
     * Handle the ProductErzap "deleted" event.
     */
    public function deleted(ProductErzap $productErzap): void
    {
        $this->syncToVariants($productErzap);
    }

    private function syncToVariants(ProductErzap $erzap)
    {
        // 1. Update all local variants matched by Erzap ID
        // Pilih harga diskon jika ada dan > 0, jika tidak gunakan harga normal
        $finalPrice = ($erzap->discount_price && $erzap->discount_price > 0)
            ? $erzap->discount_price
            : $erzap->base_price;

        // 1. Ambil varian beserta data product-nya
        $variant = ProductVariant::with('product')
            ->where('erzap_item_id', $erzap->erzap_id)
            ->first();

        // 2. Pastikan data ditemukan
        if ($variant && $variant->product) {

            // 3. Gunakan IF untuk cek is_second dari parent product
            if ($variant->product->is_second == true) {
                // Jika is_second = true, update stock saja
                $variant->update([
                    'stock' => $erzap->stock
                ]);
            } else {
                // Jika is_second = false, update price dan stock
                $variant->update([
                    'price' => $finalPrice,
                    'stock' => $erzap->stock
                ]);
            }
        }
        // ProductVariant::where('erzap_item_id', $erzap->erzap_id)
        //     ->update([
        //         'price' => $finalPrice,
        //         'stock' => $erzap->stock
        //     ]);

        // 2. Find all distinct parent products affected by this Erzap ID change
        $parentProductIds = ProductVariant::where('erzap_item_id', $erzap->erzap_id)
            ->pluck('product_id')
            ->unique();

        // 3. Re-calculate denormalized fields for each parent product
        foreach ($parentProductIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $variants = $product->variants()->get();

                $totalStock = $variants->sum('stock');
                // find min price greater than 0
                $startingPrice = $variants->where('price', '>', 0)->min('price');
                $hasActiveErzap = $variants->whereNotNull('erzap_item_id')->count() > 0;

                $product->update([
                    'total_stock' => $totalStock,
                    'starting_price' => $startingPrice,
                    'has_active_erzap' => $hasActiveErzap,
                ]);
            }
        }
    }
}
