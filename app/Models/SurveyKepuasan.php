<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyKepuasan extends Model
{
    use HasFactory;

    protected $table = 'survei_kepuasan';
    public $timestamps = false; // Karena tidak ada created_at/updated_at

    protected $fillable = [
        'pelayanan_id',
        'skor_kepuasan',
        'rekomendasi',
        'saran_masukan',
        'waktu_isi',
    ];

    /**
     * Otomatis konversi kolom JSON ke array dan sebaliknya.
     */
    protected $casts = [
        'skor_kepuasan' => 'array',
        'saran_masukan' => 'array',
        'rekomendasi' => 'boolean',
    ];

    public function pelayanan()
    {
        return $this->belongsTo(Pelayanan::class);
    }
}