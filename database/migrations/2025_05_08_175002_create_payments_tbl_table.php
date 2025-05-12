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
        Schema::create('payments_tbl', function (Blueprint $table) {
            $table->unsignedBigInteger('paymentID', true);
            $table->unsignedBigInteger('requestID')->index('fk_payments_tbl_requests_tbl');
            $table->unsignedBigInteger('userID')->index('fk_payments_tbl_users_tbl');
            $table->decimal('amount', 10);
            $table->unsignedBigInteger('modeID')->index('fk_payments_tbl_paymentmethod_tbl');
            $table->unsignedBigInteger('paymentStatusID')->index('fk_payments_tbl_paymentstatus_tbl');
            $table->dateTime('paymentDate')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_tbl');
    }
};
