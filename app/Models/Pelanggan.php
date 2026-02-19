<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'user_id',
        'no_wa',
        'alamat',
        'link_maps',
        'foto_rumah',
        'paket_id',
        'status',
        'tgl_registrasi',
        'qr_token',
        'qr_token_generated_at',
    ];

    protected $casts = [
        'tgl_registrasi' => 'datetime',
        'qr_token_generated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_id');
    }

    // Alias untuk kompatibilitas lama
    public function paketWifi()
    {
        return $this->belongsTo(PaketWifi::class, 'paket_id');
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function pppoeAccount()
    {
        return $this->hasOne(PppoeAccount::class);
    }

    public function aktivasiKodes()
    {
        return $this->hasMany(AktivasiKode::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function tagihanNunggak()
    {
        return $this->tagihans()->where('status', 'nunggak')->get();
    }

    public function getTotalTunggakanAttribute()
    {
        return $this->tagihans()
            ->where('status', 'nunggak')
            ->sum('jumlah');
    }

    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    /*
    |--------------------------------------------------------------------------
    | QR TOKEN METHODS
    |--------------------------------------------------------------------------
    */

    public function generateQrToken(): string
    {
        $token = hash(
            'sha256',
            $this->id . $this->user_id . Str::random(32) . now()->timestamp
        );

        $this->update([
            'qr_token' => $token,
            'qr_token_generated_at' => now(),
        ]);

        return $token;
    }

    public function ensureHasQrToken(): string
    {
        if (empty($this->qr_token)) {
            return $this->generateQrToken();
        }

        return $this->qr_token;
    }

    public function getQrScanUrl(): string
    {
        return route('qr.scan', [
            'token' => $this->ensureHasQrToken()
        ]);
    }

    public function getQrImageUrl(int $size = 250): string
    {
        $url = urlencode($this->getQrScanUrl());

        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$url}&bgcolor=ffffff&color=1a1a2e&qzone=2";
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeWithQrToken($query)
    {
        return $query->whereNotNull('qr_token');
    }
}
