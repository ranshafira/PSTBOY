<?php
// app/Models/JadwalSwap.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSwap extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_asal_id',
        'jadwal_tujuan_id',
        'pengaju_id',
        'target_id',
        'status',
        'alasan',
        'catatan_admin',
        'approved_at',
        'rejected_at',
        'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function jadwalAsal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_asal_id');
    }

    public function jadwalTujuan()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_tujuan_id');
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
