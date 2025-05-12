<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_tbl', function (Blueprint $table) {
            $table->unsignedBigInteger('userID', true);
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('emailAddress', 50)->unique('emailaddress');
            $table->string('contactNumber', 11);
            $table->string('homeAddress', 100);
            $table->string('IDCard');
            $table->unsignedBigInteger('roleID')->index('fk_users_tbl_roles_tbl');
            $table->string('password', 255);
            $table->dateTime('verifiedAt')->nullable();
            $table->dateTime('dateCreated')->useCurrentOnUpdate()->useCurrent();
            $table->dateTime('dateUpdated')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_tbl');
    }
};
