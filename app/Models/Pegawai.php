<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pegawai';

protected $fillable = [
    'nama_lengkap',
    'email',
    'kata_sandi',
    'peran',
];
    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];
}


