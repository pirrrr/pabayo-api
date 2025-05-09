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
            $table->foreign(['serviceID'], 'FK_requests_tbl_services_tbl')->references(['serviceID'])->on('services_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['statusID'], 'FK_requests_tbl_status_table')->references(['statusID'])->on('status_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['customerID'], 'FK_requests_tbl_users_tbl')->references(['userID'])->on('users_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['courierID'], 'FK_requests_tbl_users_tbl_2')->references(['userID'])->on('users_tbl')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests_tbl', function (Blueprint $table) {
            $table->dropForeign('FK_requests_tbl_services_tbl');
            $table->dropForeign('FK_requests_tbl_status_table');
            $table->dropForeign('FK_requests_tbl_users_tbl');
            $table->dropForeign('FK_requests_tbl_users_tbl_2');
        });
    }
};
