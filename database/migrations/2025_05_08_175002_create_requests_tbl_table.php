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
        Schema::create('requests_tbl', function (Blueprint $table) {
            $table->integer('requestID', true);
            $table->integer('customerID')->index('fk_requests_tbl_users_tbl');
            $table->integer('serviceID')->index('fk_requests_tbl_services_tbl');
            $table->integer('courierID')->index('fk_requests_tbl_users_tbl_2');
            $table->integer('statusID')->index('fk_requests_tbl_status_table');
            $table->date('pickupDate')->nullable();
            $table->date('deliveryDate')->nullable();
            $table->integer('sackQuantity')->nullable();
            $table->string('comment', 300)->nullable();
            $table->dateTime('dateCreated')->useCurrentOnUpdate()->useCurrent();
            $table->dateTime('dateUpdated')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests_tbl');
    }
};
