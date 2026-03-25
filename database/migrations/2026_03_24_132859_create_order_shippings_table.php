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
        Schema::create('order_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('biteship_order_id')->nullable();
            $table->string('courier_company')->nullable(); // jne, gojek
            $table->string('courier_type')->nullable(); // reg, instant
            $table->string('tracking_number')->nullable();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->string('status')->default('ALLOCATED'); // ALLOCATED, PICKED_UP, DELIVERED
            $table->json('shipping_payload')->nullable(); // Simpan respons Biteship
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shippings');
    }
};
