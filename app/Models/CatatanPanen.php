<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPanen extends Model
{
    use HasFactory;

    protected $table = 'catatan_panen';
    protected $fillable = ['id_pegawai', 'tanggal', 'id_area_kerja', 'jumlah_tandan', 'berat_kg', 'catatan'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function areaKerja()
    {
        return $this->belongsTo(AreaKerja::class, 'id_area_kerja');
    }
}

