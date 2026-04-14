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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_second')->default(false)->after('is_active');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreignId('trade_in_id')->nullable()->constrained('trade_ins')->nullOnDelete()->after('erzap_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['trade_in_id']);
            $table->dropColumn('trade_in_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_second');
        });
    }
};
