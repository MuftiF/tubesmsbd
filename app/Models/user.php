<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    // UBAH: ganti 'no.hp' menjadi 'no_hp'
    protected $fillable = ['name', 'no_hp', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Tentukan field yang digunakan untuk authentication
     * UBAH: dari 'no.hp' menjadi 'no_hp'
     */
    public function getAuthIdentifierName()
    {
        return 'no_hp';
    }

    /**
     * OVERRIDE: Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->attributes['no_hp'];
    }

    /**
     * OVERRIDE: Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Jika perlu akses email untuk notifikasi (opsional)
     * Atau bisa return null jika tidak menggunakan email
     */
    public function getEmailForPasswordReset()
    {
        // Kembalikan null atau no_hp jika ingin digunakan untuk reset
        return null;
        // atau: return $this->no_hp;
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail()
    {
        // Kembalikan null jika tidak menggunakan email notifications
        return null;
        // atau email alternatif jika ada
    }
}