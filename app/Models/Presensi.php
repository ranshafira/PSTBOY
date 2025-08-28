<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    public $timestamps = false;
    public $incrementing = false; // <-- Tambahkan ini karena tidak ada kolom auto-increment
    protected $primaryKey = null; // <-- Tambahkan ini untuk menonaktifkan 'id' sebagai primary key
    protected $keyType = 'string'; // <-- Tambahkan ini, bisa 'int' atau 'string' (tidak terlalu penting di sini)

    protected $fillable = [
        'petugas_id',
        'tanggal',
        'waktu_datang',
        'waktu_pulang',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
