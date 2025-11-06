<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'id_pegawai',
        'tanggal_absen',
        'id_shift',
        'id_area_kerja',
        'id_status_absensi',
        'waktu_masuk',
        'waktu_keluar',
        'total_menit',
        'catatan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function areaKerja()
    {
        return $this->belongsTo(AreaKerja::class, 'id_area_kerja');
    }

    public function statusAbsensi()
    {
        return $this->belongsTo(StatusAbsensi::class, 'id_status_absensi');
    }
}
