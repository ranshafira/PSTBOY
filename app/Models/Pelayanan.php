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
        'nama_pengunjung',
        'instansi_pengunjung',
        'no_hp',
        'email',
        'jenis_kelamin',
        'pendidikan',
        'path_surat_pengantar',
        'kebutuhan_pengunjung',
        'status_penyelesaian',
        'deskripsi_hasil',
        'jenis_output',
        'path_dokumen_hasil',
        'perlu_tindak_lanjut',
        'tanggal_tindak_lanjut',
        'catatan_tindak_lanjut',
        'catatan_tambahan',
        'waktu_mulai_sesi',
        'waktu_selesai_sesi',
        'survey_token',
        'survey_completed_at',
    ];


    protected $casts = [
        'jenis_output' => 'array',
        'waktu_mulai_sesi' => 'datetime',     
        'waktu_selesai_sesi' => 'datetime',    
        'tanggal_tindak_lanjut' => 'date',     
        'survey_completed_at' => 'datetime', 
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

    public function surveyKepuasan()
    {
        return $this->hasOne(SurveyKepuasan::class, 'pelayanan_id');
    }
    

}
