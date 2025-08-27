<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    public $timestamps = false; // Tidak menggunakan created_at & updated_at

    protected $fillable = [
        'petugas_id',
        'tanggal',
        'waktu_datang',
        'waktu_pulang',
    ];

    // Relasi ke User, dengan foreign key 'petugas_id'
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}