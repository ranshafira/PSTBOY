<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'username',
        'password',
        'role_id',
        'email',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function role()
{
    return $this->belongsTo(Role::class);
}



}