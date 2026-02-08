<?php

// database/migrations/xxxx_xx_xx_add_referensi_to_buku_kas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buku_kas', function (Blueprint $table) {
            $table->unsignedBigInteger('referensi_tagihan_id')->nullable()->after('keterangan');
            $table->foreign('referensi_tagihan_id')->references('id')->on('tagihans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('buku_kas', function (Blueprint $table) {
            $table->dropForeign(['referensi_tagihan_id']);
            $table->dropColumn('referensi_tagihan_id');
        });
    }
};