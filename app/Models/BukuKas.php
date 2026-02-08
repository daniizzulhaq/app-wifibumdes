<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jenis',
        'nominal',
        'kategori',
        'keterangan',
    ];

    protected $dates = ['tanggal'];
}