<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buyback_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Flagship High", "Mid-Range B", "Entry Level", dll.
            $table->decimal('min_price', 15, 2)->nullable(); // Range harga minimum penjualan HP
            $table->decimal('max_price', 15, 2)->nullable(); // Range harga maksimum penjualan HP

            // Kolom JSON untuk menampung semua kategori dan kualifikasi potongan
            // Struktur: { "Kategori": [{ "name": "...", "type": "fixed|percentage", "value": 0 }] }
            $table->json('rules')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyback_tiers');
    }
};
