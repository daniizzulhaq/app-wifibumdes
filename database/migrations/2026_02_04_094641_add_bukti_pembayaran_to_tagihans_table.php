<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('status');
            $table->string('metode_pembayaran')->nullable()->after('bukti_pembayaran');
            $table->text('catatan_pembayaran')->nullable()->after('metode_pembayaran');
        });
    }

    public function down()
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['bukti_pembayaran', 'metode_pembayaran', 'catatan_pembayaran']);
        });
    }
};