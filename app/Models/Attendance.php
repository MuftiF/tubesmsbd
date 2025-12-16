<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CatatanPanen;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'photos',
        'description',
        'note'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'photos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // RELASI BARU: Hubungkan dengan CatatanPanen berdasarkan user_id dan tanggal yang sama
    public function panen()
    {
        return $this->hasOne(CatatanPanen::class, 'id_pegawai', 'user_id')
            ->whereDate('tanggal', $this->date);
    }

    // RELASI ALTERNATIF: Jika tanggal tidak match persis
    public function catatanPanen()
    {
        return $this->hasOne(CatatanPanen::class, 'id_pegawai', 'user_id')
            ->where('tanggal', $this->date->toDateString());
    }
}