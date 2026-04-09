<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = ['id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get subtotal for this cart item.
     */
    public function getSubtotalAttribute(): float
    {
        return $this->qty * ($this->productVariant->price ?? 0);
    }
}
