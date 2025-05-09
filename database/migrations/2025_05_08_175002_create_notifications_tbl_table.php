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
        Schema::create('notifications_tbl', function (Blueprint $table) {
            $table->integer('notificationID', true);
            $table->integer('userID')->index('fk_notifications_tbl_users_tbl');
            $table->string('message', 150);
            $table->boolean('isRead')->default(false);
            $table->dateTime('dateCreated')->useCurrentOnUpdate()->useCurrent();
            $table->dateTime('dateUpdated')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications_tbl');
    }
};
