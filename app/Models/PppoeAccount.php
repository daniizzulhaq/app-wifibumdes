<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PppoeAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'username_pppoe',
        'password_pppoe',
    ];

    protected $hidden = [
        'password_pppoe',
    ];

    /**
     * Relasi ke pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}