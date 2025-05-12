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
        Schema::table('requests_tbl', function (Blueprint $table) {
        $table->foreign('ownerID', 'fk_requests_tbl_users_tbl_3')
              ->references('userID')
              ->on('users_tbl')
              ->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests_tbl', function (Blueprint $table) {
        $table->dropForeign('fk_requests_tbl_users_tbl_3');
    });
    }
};
