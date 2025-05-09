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
        Schema::table('messages_tbl', function (Blueprint $table) {
            $table->foreign(['senderID'], 'FK__users_tbl')->references(['userID'])->on('users_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['receiverID'], 'FK_messages_tbl_users_tbl')->references(['userID'])->on('users_tbl')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages_tbl', function (Blueprint $table) {
            $table->dropForeign('FK__users_tbl');
            $table->dropForeign('FK_messages_tbl_users_tbl');
        });
    }
};
