<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    // Konversi kolom JSON menjadi array otomatis di Laravel
    protected $casts = [
        'shipping_address_snapshot' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments()
    {
        // hasMany karena user bisa saja mencoba bayar berulang kali jika gagal
        return $this->hasMany(OrderPayment::class);
    }
    public function shipping()
    {
        // hasOne karena 1 order biasanya 1 pengiriman
        return $this->hasOne(OrderShipping::class);
    }
}
