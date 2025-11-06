<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajian';
    protected $fillable = [
        'id_pegawai', 'bulan', 'tahun', 'gaji_pokok', 'bonus_panen',
        'upah_lembur', 'potongan', 'gaji_bersih'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}

