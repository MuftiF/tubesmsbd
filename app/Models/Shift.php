<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shift';
    protected $fillable = ['kode', 'nama', 'jam_mulai', 'jam_selesai', 'durasi_menit', 'keterangan'];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_shift');
    }
}

