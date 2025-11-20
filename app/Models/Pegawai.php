<?php
// app/Models/Pegawai.php
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
        'name', // Match database column
        'email',
        'password', // Match database column
        'role', // Match database column
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
