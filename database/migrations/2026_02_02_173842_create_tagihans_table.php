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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            $table->string('bulan', 7); // format: YYYY-MM (contoh: 2024-01)
            $table->decimal('jumlah', 10, 2);
            $table->enum('status', ['lunas', 'nunggak'])->default('nunggak');
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['pelanggan_id', 'bulan']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};