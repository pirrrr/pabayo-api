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
        Schema::table('payments_tbl', function (Blueprint $table) {
            $table->foreign(['modeID'], 'FK_payments_tbl_paymentmethod_tbl')->references(['modeID'])->on('paymentmethods_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['paymentStatusID'], 'FK_payments_tbl_paymentstatus_tbl')->references(['paymentStatusID'])->on('paymentstatuses_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['requestID'], 'FK_payments_tbl_requests_tbl')->references(['requestID'])->on('requests_tbl')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['userID'], 'FK_payments_tbl_users_tbl')->references(['userID'])->on('users_tbl')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments_tbl', function (Blueprint $table) {
            $table->dropForeign('FK_payments_tbl_paymentmethod_tbl');
            $table->dropForeign('FK_payments_tbl_paymentstatus_tbl');
            $table->dropForeign('FK_payments_tbl_requests_tbl');
            $table->dropForeign('FK_payments_tbl_users_tbl');
        });
    }
};
