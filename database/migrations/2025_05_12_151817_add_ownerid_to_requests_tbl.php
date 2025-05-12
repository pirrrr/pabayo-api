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
        $table->unsignedBigInteger('ownerID')->nullable()->after('requestID');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('requests_tbl', function (Blueprint $table) {
        $table->dropColumn('ownerID');
    });
}
};
