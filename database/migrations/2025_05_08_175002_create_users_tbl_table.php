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
        Schema::create('users_tbl', function (Blueprint $table) {
            $table->integer('userID', true);
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('emailAddress', 50)->unique('emailaddress');
            $table->string('contactNumber', 11);
            $table->string('homeAddress', 100);
            $table->string('IDCard');
            $table->integer('roleID')->index('fk_users_tbl_roles_tbl');
            $table->string('password', 255);
            $table->dateTime('verifiedAt')->default('now()');
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
