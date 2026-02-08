<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->date('tanggal_jatuh_tempo')->nullable()->after('bulan');
        });
    }

    public function down()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn('tanggal_jatuh_tempo');
        });
    }
};