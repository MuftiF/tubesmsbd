<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusAbsensi extends Model
{
    use HasFactory;

    protected $table = 'status_absensi';
    protected $fillable = ['kode', 'nama', 'keterangan'];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_status_absensi');
    }
}

