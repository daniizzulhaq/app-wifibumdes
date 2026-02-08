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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('alamat');
            $table->string('no_wa', 20);
            $table->text('link_maps')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->foreignId('paket_id')->constrained('paket_wifi')->onDelete('restrict');
            $table->enum('status', ['aktif', 'nonaktif', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};