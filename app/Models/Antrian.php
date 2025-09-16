<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan yang ada di database
    protected $table = 'antrian';

    /**
     * Field yang boleh diisi secara mass assignment
     */
    protected $fillable = [
        'nomor_antrian',
        'jenis_layanan_id',
        // tambahkan field lain jika perlu
    ];

    /**
     * Relasi: Antrian milik satu JenisLayanan
     */
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }

    /**
     * Relasi: Antrian memiliki satu Pelayanan
     */
    public function pelayanan()
    {
        return $this->hasOne(Pelayanan::class, 'antrian_id');
    }
}
