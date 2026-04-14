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
        Schema::create('trade_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('target_product_id')->constrained('products')->cascadeOnDelete();
            
            // Info HP Lama
            $table->string('old_phone_brand');
            $table->string('old_phone_model');
            $table->string('old_phone_ram')->nullable();
            $table->string('old_phone_storage')->nullable();
            $table->text('old_phone_minus_desc')->nullable();
            
            // Taksiran Harga dari Admin
            $table->decimal('appraised_value', 15, 2)->nullable();
            
            $table->string('status')->default('PENDING'); 
            // PENDING, OFFERED, WAITING_FOR_DEVICE, INSPECTING, PAYING, COMPLETED, CANCELLED
            
            // Resi logistik dari pengguna ke toko
            $table->string('customer_shipping_receipt')->nullable();
            
            // Link ke tabel orders untuk transaksi akhir jika dilanjutkan
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_ins');
    }
};
