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
        Schema::table('users_tbl', function (Blueprint $table) {
            $table->foreign(['roleID'], 'FK_users_tbl_roles_tbl')->references(['roleID'])->on('roles_tbl')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_tbl', function (Blueprint $table) {
            $table->dropForeign('FK_users_tbl_roles_tbl');
        });
    }
};
