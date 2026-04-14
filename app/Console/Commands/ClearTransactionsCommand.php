<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:clear-transactions';

    /**
     * The console command description.
     */
    protected $description = 'Bersihkan semua data transaksi (Orders, Trade-Ins, Carts) untuk mencegah inkonsistensi saat testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!app()->environment('local', 'testing', 'development')) {
            $this->error('Tindakan ini dilarang keras di production!');
            return Command::FAILURE;
        }

        if (!$this->confirm('PERINGATAN: Semua data Trsansaksi (Order, Keranjang, Trade-In) akan DIHAPUS PERMANEN dari database ini. Yakin?', false)) {
            $this->info('Aksi dibatalkan.');
            return Command::SUCCESS;
        }

        $this->info('Memulai pembersihan tabel transaksi...');

        Schema::disableForeignKeyConstraints();

        try {
            // Hapus isi tabel dari yang paling ujung relasinya
            DB::table('order_payments')->delete();
            DB::table('order_shippings')->delete();
            DB::table('order_items')->delete();
            DB::table('orders')->delete();
            
            DB::table('trade_in_unit_options')->delete();
            DB::table('trade_ins')->delete();
            
            DB::table('cart_items')->delete();
            DB::table('carts')->delete();

            // Opsional: Hapus media/media spatie yang berkaitan dengan trade_ins
            // Menghindari foto sampah memenuhi storage
            $models = [
                'App\\Models\\TradeIn',
                'App\\Models\\Order'
            ];
            DB::table('media')->whereIn('model_type', $models)->delete();

            Schema::enableForeignKeyConstraints();
            
            $this->info('✅ Pembersihan selesai! Database transaksi telah dikembalikan ke kondisi kosong.');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
