<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel pivot ini sudah tidak diperlukan setelah refactor ke BuybackTier + JSON Rules.
// Rules kini disimpan langsung di kolom `rules` (JSON) di tabel `buyback_tiers`.
// File ini dipertahankan agar urutan migrasi tidak terganggu.
return new class extends Migration
{
    public function up(): void
    {
        // Tidak ada tabel yang dibuat - pivot sudah digantikan oleh JSON rules di buyback_tiers
    }

    public function down(): void
    {
        // Tidak ada yang perlu di-rollback
    }
};
