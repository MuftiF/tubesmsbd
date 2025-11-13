<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaKerja extends Model
{
    use HasFactory;

    protected $table = 'area_kerja';
    protected $fillable = ['kode', 'nama', 'afdeling', 'keterangan'];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_area_kerja');
    }
}

