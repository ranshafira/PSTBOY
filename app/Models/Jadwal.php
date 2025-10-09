<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = ['user_id', 'tanggal', 'shift'];

    protected $dates = ['tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Jika ada relationship swap, pastikan benar
    public function swapRequestsAsSource()
    {
        return $this->hasMany(JadwalSwap::class, 'jadwal_asal_id');
    }

    public function swapRequestsAsTarget()
    {
        return $this->hasMany(JadwalSwap::class, 'jadwal_tujuan_id');
    }
}
