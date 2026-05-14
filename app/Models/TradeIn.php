<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TradeIn extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetProduct()
    {
        return $this->belongsTo(Product::class, 'target_product_id');
    }

    public function buybackDevice()
    {
        return $this->belongsTo(BuybackDevice::class, 'buyback_device_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function unitOptions()
    {
        return $this->hasMany(TradeInUnitOption::class);
    }

    public function registerMediaCollections(): void
    {
        // Foto fisik dari user
        $this->addMediaCollection('phone_condition');
        // Foto fisik dari bukti admin
        $this->addMediaCollection('admin_inspection_photos');
    }
}
