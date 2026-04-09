<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get total item count in cart.
     */
    public function getTotalQtyAttribute(): int
    {
        return $this->items->sum('qty');
    }

    /**
     * Get total price of all items in cart.
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->qty * ($item->productVariant->price ?? 0);
        });
    }
}
