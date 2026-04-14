<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeInUnitOption extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'is_selected' => 'boolean',
    ];

    public function tradeIn()
    {
        return $this->belongsTo(TradeIn::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
