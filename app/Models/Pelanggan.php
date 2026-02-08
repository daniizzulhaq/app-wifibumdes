<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'user_id',
        'alamat',
        'no_wa',
        'link_maps',
        'foto_rumah',
        'paket_id',
        'status',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke paket wifi
     */
    public function paket()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_id');
    }

    public function paketWifi()
{
    return $this->belongsTo(PaketWifi::class, 'paket_id');
}

    /**
     * Relasi ke pppoe account
     */
    public function pppoeAccount()
    {
        return $this->hasOne(PppoeAccount::class);
    }

    /**
     * Relasi ke tagihan
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    /**
     * Relasi ke aktivasi kode
     */
    public function aktivasiKodes()
    {
        return $this->hasMany(AktivasiKode::class);
    }

    /**
     * Get tagihan yang belum lunas
     */
    public function tagihanNunggak()
    {
        return $this->tagihans()->where('status', 'nunggak')->get();
    }

    /**
     * Get total tunggakan
     */
    public function getTotalTunggakanAttribute()
    {
        return $this->tagihans()->where('status', 'nunggak')->sum('jumlah');
    }

    /**
     * Check if pelanggan aktif
     */
    public function isAktif()
    {
        return $this->status === 'aktif';
    }
}