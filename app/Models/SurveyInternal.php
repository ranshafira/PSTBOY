<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyInternal extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'survey_internals';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pelayanan_id',
        'skor_keseluruhan',
        'skor_petugas',
        'saran',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Pelayanan.
     */
    public function pelayanan()
    {
        return $this->belongsTo(Pelayanan::class);
    }
}
