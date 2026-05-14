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
        Schema::create('buyback_devices', function (Blueprint $table) {
            $table->id();
            // Terhubung ke tabel brands yang sudah ada di project
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();

            // Tier otomatis ter-assign berdasarkan base_price saat save
            $table->foreignId('buyback_tier_id')->nullable()->constrained('buyback_tiers')->nullOnDelete();

            $table->string('model_name');        // cth: 'iPhone 15 Pro Max'
            $table->string('ram')->nullable();   // cth: '8GB' (bisa null untuk iPhone)
            $table->string('storage');           // cth: '256GB'

            // Harga beli acuan kondisi sempurna 100%.
            // Digunakan untuk: (1) auto-assign ke tier yang sesuai, (2) kalkulasi potongan persentase
            $table->decimal('base_price', 15, 2);

            $table->boolean('is_active')->default(true); // Status apakah HP ini masih diterima toko
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyback_devices');
    }
};
