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
        Schema::create('sell_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Info Perangkat yang dijual
            $table->string('phone_brand');
            $table->string('phone_model');
            $table->string('phone_ram')->nullable();
            $table->string('phone_storage')->nullable();
            $table->text('minus_desc')->nullable();
            
            // Penaksiran Harga & Status
            $table->decimal('appraised_value', 15, 2)->nullable();
            $table->string('status')->default('PENDING'); 
            // PENDING, OFFERED, WAITING_FOR_DEVICE, INSPECTING, PAYING, COMPLETED, CANCELLED
            
            // Logistik & Pembayaran
            $table->string('customer_shipping_receipt')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_phones');
    }
};
