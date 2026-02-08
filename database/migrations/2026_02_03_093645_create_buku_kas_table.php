<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_create_buku_kas_table.php

public function up()
{
    Schema::create('buku_kas', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->enum('jenis', ['pemasukan', 'pengeluaran']);
        $table->unsignedBigInteger('nominal');
        $table->enum('kategori', [
            'perbaikan',
            'perawatan',
            'pelatihan',
            'stock_barang',
            'tagihan_banwith',
            'honor_karyawan',
            'sosial',
            'donatur',
            'listrik',
            'bpjs',
            'pajak',
            'administrasi',
            'thr',
            'lain_lain'
        ]);
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('buku_kas');
}
};
