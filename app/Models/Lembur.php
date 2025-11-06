<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'lembur';
    protected $fillable = [
        'id_pegawai', 'id_absensi', 'tanggal', 'jam_mulai', 'jam_selesai', 'total_menit', 'alasan', 'disetujui_oleh'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'id_absensi');
    }

    public function penyetuju()
    {
        return $this->belongsTo(Pegawai::class, 'disetujui_oleh');
    }
}

