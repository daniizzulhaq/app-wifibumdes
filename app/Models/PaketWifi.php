<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWifi extends Model
{
    use HasFactory;

    protected $table = 'paket_wifi';

    protected $fillable = [
        'nama_paket',
        'kecepatan',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    /**
     * Relasi ke pelanggan
     */
    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'paket_id');
    }

    /**
     * Relasi ke aktivasi kode
     */
    public function aktivasiKodes()
    {
        return $this->hasMany(AktivasiKode::class, 'paket_id');
    }

    /**
     * Format harga ke Rupiah
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}