<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    use HasFactory;

    protected $table = 'pelayanan';
    
    protected $fillable = [
        'petugas_id',
        'antrian_id',
        'jenis_layanan_id',
        'nama_pelanggan',
        'instansi_pelanggan',
        'kontak_pelanggan',
        'path_surat_pengantar',
        'kebutuhan_pelanggan',
        'hasil_pelayanan',
        'waktu_mulai_sesi',
        'waktu_selesai_sesi',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id'); 
    }

    public function antrian()
    {
        return $this->belongsTo(Antrian::class, 'antrian_id'); 
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

}
