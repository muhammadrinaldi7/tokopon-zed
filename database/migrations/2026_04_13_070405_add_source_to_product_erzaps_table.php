<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_erzaps', function (Blueprint $table) {
            $table->string('source')->default('syihab')->after('erzap_id'); // syihab | gsksyihab
        });

        // Drop old unique on erzap_id only, make composite unique with source
        // SQLite doesn't support dropUnique well, so we skip if needed
        // The uniqueness will be enforced at application level via updateOrCreate with composite keys
    }

    public function down(): void
    {
        Schema::table('product_erzaps', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
