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
        Schema::create('messages_tbl', function (Blueprint $table) {
            $table->unsignedBigInteger('messageID', true);
            $table->unsignedBigInteger('senderID')->index('fk__users_tbl');
            $table->unsignedBigInteger('receiverID')->index('fk_messages_tbl_users_tbl');
            $table->string('message', 250)->nullable();
            $table->boolean('isRead')->default(false);
            $table->dateTime('sentDate')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_tbl');
    }
};
