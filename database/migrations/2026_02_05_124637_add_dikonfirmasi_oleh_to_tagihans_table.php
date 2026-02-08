<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->unsignedBigInteger('dikonfirmasi_oleh')->nullable()->after('tanggal_bayar');
            $table->foreign('dikonfirmasi_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropForeign(['dikonfirmasi_oleh']);
            $table->dropColumn('dikonfirmasi_oleh');
        });
    }
};