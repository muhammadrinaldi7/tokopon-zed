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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: iPhone 15 Pro Max
            $table->string('slug')->unique(); // Untuk keperluan SEO URL
            $table->foreignIdFor(\App\Models\Category::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(\App\Models\Brand::class)->nullable()->constrained()->nullOnDelete();
            $table->longText('description')->nullable();
            $table->json('specifications')->nullable();
            
            // Kolom Denormalisasi Hasil Observer Erzap (Agar performa Landing Page ngebut)
            $table->decimal('starting_price', 15, 2)->nullable();
            $table->integer('total_stock')->default(0);
            $table->string('thumbnail_image')->nullable();
            $table->boolean('has_active_erzap')->default(false); // Penanda bahwa produk ini memiliki setidaknya 1 varian Erzap aktif
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
