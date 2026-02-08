<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Untuk MySQL, kita perlu mengubah enum
        DB::statement("ALTER TABLE `tagihans` MODIFY `status` ENUM('belum_bayar', 'nunggak', 'menunggu_konfirmasi', 'lunas') NOT NULL DEFAULT 'belum_bayar'");
    }

    public function down()
    {
        // Kembalikan ke enum lama
        DB::statement("ALTER TABLE `tagihans` MODIFY `status` ENUM('belum_bayar', 'nunggak', 'lunas') NOT NULL DEFAULT 'belum_bayar'");
    }
};