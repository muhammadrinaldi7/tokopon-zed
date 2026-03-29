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
        Schema::create('product_erzaps', function (Blueprint $table) {
            $table->string('erzap_id')->primary(); // Kode unik dari erzap (syihab: kode)
            $table->string('name')->nullable();
            $table->decimal('base_price', 15, 2)->default(0); // harga_jual dari Erzap
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->integer('stock')->default(0); // available_stok dari Erzap
            $table->string('barcode')->nullable();
            $table->json('raw_data')->nullable(); // JSON mentah untuk referensi jika ada field baru
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_erzaps');
    }
};
