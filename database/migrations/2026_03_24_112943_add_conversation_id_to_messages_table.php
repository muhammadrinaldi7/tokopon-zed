<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Conversation;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom conversation_id sebagai nullable dulu
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('conversation_id')->nullable()->after('id');
        });

        // Untuk pesan-pesan lama, buat conversation per user
        $existingMessages = DB::table('messages')->whereNull('conversation_id')->get();
        $userConversations = [];

        foreach ($existingMessages as $msg) {
            if (!isset($userConversations[$msg->user_id])) {
                $conv = Conversation::firstOrCreate(
                    ['user_id' => $msg->user_id],
                    ['status' => 'open']
                );
                $userConversations[$msg->user_id] = $conv->id;
            }

            DB::table('messages')
                ->where('id', $msg->id)
                ->update(['conversation_id' => $userConversations[$msg->user_id]]);
        }

        // Sekarang jadikan NOT NULL dan tambahkan foreign key
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('conversation_id')->nullable(false)->change();
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn('conversation_id');
        });
    }
};
