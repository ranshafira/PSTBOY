<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    use HasFactory;

    protected $table = 'bukutamu_nonpst';
    public $timestamps = false;

    protected $fillable = [
        'nama_tamu',
        'instansi_tamu',
        'kontak_tamu',
        'keperluan',
        'tujuan',
    ];
}