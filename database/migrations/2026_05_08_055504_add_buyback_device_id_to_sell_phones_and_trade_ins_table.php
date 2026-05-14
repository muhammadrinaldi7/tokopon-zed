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
        Schema::table('sell_phones', function (Blueprint $table) {
            $table->foreignId('buyback_device_id')->nullable()->constrained('buyback_devices')->nullOnDelete();
        });

        Schema::table('trade_ins', function (Blueprint $table) {
            $table->foreignId('buyback_device_id')->nullable()->constrained('buyback_devices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trade_ins', function (Blueprint $table) {
            $table->dropForeign(['buyback_device_id']);
            $table->dropColumn('buyback_device_id');
        });

        Schema::table('sell_phones', function (Blueprint $table) {
            $table->dropForeign(['buyback_device_id']);
            $table->dropColumn('buyback_device_id');
        });
    }
};
