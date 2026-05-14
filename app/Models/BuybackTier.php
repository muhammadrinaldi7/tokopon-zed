<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuybackTier extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'rules'     => 'array',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
    ];

    // Relasi ke perangkat-perangkat yang masuk di tier ini
    public function devices()
    {
        return $this->hasMany(BuybackDevice::class);
    }

    /**
     * Cari tier yang sesuai berdasarkan harga.
     * Digunakan untuk auto-assign tier saat device disimpan.
     */
    public static function findByPrice(float $price): ?self
    {
        return self::where(function ($q) use ($price) {
            $q->where('min_price', '<=', $price)
              ->where('max_price', '>=', $price);
        })->first();
    }

    /**
     * Ambil semua kategori yang ada dalam rules JSON.
     * Return: array kategori => array item rules
     */
    public function getRulesByCategory(): array
    {
        return $this->rules ?? [];
    }

    /**
     * Label range harga untuk ditampilkan di UI.
     */
    public function getPriceRangeLabelAttribute(): string
    {
        $min = $this->min_price ? 'Rp ' . number_format($this->min_price, 0, ',', '.') : '0';
        $max = $this->max_price ? 'Rp ' . number_format($this->max_price, 0, ',', '.') : '~';
        return "{$min} – {$max}";
    }
}
