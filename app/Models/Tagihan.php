<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    protected $fillable = [
        'pelanggan_id',
        'bulan',
        'tahun',
        'tanggal_jatuh_tempo',
        'jumlah',
        'status',
        'tanggal_bayar',
        'bukti_pembayaran',
        'metode_pembayaran',
        'catatan_pembayaran',
        'dikonfirmasi_oleh', // Tambahkan ini
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function paket_wifi()
    {
        return $this->hasOneThrough(
            PaketWifi::class,
            Pelanggan::class,
            'id',
            'id',
            'pelanggan_id',
            'paket_id'
        );
    }

    public function getJumlahFormatAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getBulanFormatAttribute()
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return ($namaBulan[$this->bulan] ?? 'N/A') . ' ' . $this->tahun;
    }

    public function isLunas()
    {
        return $this->status === 'lunas';
    }

    public function isNunggak()
    {
        return $this->status === 'nunggak';
    }

    public function isMenungguKonfirmasi()
    {
        return $this->status === 'menunggu_konfirmasi';
    }

    public function scopeNunggak($query)
    {
        return $query->where('status', 'nunggak');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeMenungguKonfirmasi($query)
    {
        return $query->where('status', 'menunggu_konfirmasi');
    }

     public function konfirmator()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

     public function bukuKas()
    {
        return $this->hasOne(BukuKas::class, 'referensi_tagihan_id');
    }
}