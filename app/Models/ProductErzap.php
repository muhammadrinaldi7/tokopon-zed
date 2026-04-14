<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductErzap extends Model
{
    protected $primaryKey = 'erzap_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'erzap_id',
        'source',
        'name',
        'base_price',
        'discount_price',
        'stock',
        'barcode',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'erzap_item_id', 'erzap_id');
    }
}
