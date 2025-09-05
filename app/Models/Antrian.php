<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrian';

    protected $fillable = [
        'nomor_antrian',
        'jenis_layanan_id',
    ];

    public function jenisLayanan() {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }
    
    public function pelayanan()
    {
        return $this->hasOne(Pelayanan::class, 'antrian_id');
    }

}