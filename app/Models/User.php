<?php

namespace App\Models;

// 1. Tambahkan baris 'use' ini
use JeroenNoten\LaravelAdminLte\Contracts\AdminLteUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// 2. Tambahkan 'implements AdminLteUser' setelah 'Authenticatable'
class User extends Authenticatable implements AdminLteUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'username',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 3. Baris 'email_verified_at' dihapus karena tidak ada di database Anda
        'password' => 'hashed',
    ];
}