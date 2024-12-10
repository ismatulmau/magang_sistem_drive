<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('max_storage')->default(0); // kapasitas maksimal dalam byte
            $table->string('allowed_file_types')->nullable(); // jenis file yang diizinkan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('max_storage');
            $table->dropColumn('allowed_file_types');
        });
    }
};
