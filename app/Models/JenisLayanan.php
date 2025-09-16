<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    // Secara eksplisit menyebutkan nama tabel (penting karena bukan bentuk jamak default Laravel)
    protected $table = 'jenis_layanan';

    // Relasi: JenisLayanan memiliki banyak Antrian
    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'jenis_layanan_id');
    }
}
