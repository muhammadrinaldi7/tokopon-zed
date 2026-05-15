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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('accurate_customer_id')->nullable();
            $table->integer('accurate_vendor_id')->nullable();
            $table->string('accurate_customer_no')->nullable();
            $table->string('accurate_vendor_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['accurate_customer_id', 'accurate_vendor_id', 'accurate_customer_no', 'accurate_vendor_no']);
        });
    }
};
