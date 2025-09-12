<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    public $timestamps = false;
    public $incrementing = false; // tidak ada kolom auto-increment
    protected $primaryKey = null; // menonaktifkan 'id' sebagai primary key
    protected $keyType = 'string'; // bisa 'int' atau 'string' (tidak terlalu penting di sini)

    protected $fillable = [
        'petugas_id',
        'tanggal',
        'waktu_datang',
        'waktu_pulang',
        'shift',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
