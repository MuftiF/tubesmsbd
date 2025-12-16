<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapot extends Model
{
    use HasFactory;

    // Nama tabel (penting karena nama tabel berbeda dari plural model)
    protected $table = 'rapot';

    protected $fillable = [
        'id_user',
        'evaluator_id',
        'periode',
        'periode_start',
        'periode_end',
        'total_jam',
        'hari_kerja',
        'rata_jam_perhari',
        'nilai',
        'detail_absen',
        'evaluasi_kerja',
        'saran_perbaikan',
        'catatan',
        'data_evaluasi',
        'status',
        'tipe',
        'generated_at',
        'regenerated_at',
    ];

    protected $casts = [
        'periode_start' => 'date',
        'periode_end' => 'date',
        'total_jam' => 'double',
        'rata_jam_perhari' => 'double',
        'nilai' => 'double',
        'detail_absen' => 'array',
        'data_evaluasi' => 'array',
        'generated_at' => 'datetime',
        'regenerated_at' => 'datetime',
    ];

    // Relasi dengan user (pegawai yang dinilai)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi dengan evaluator (admin/manager yang menilai)
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Scope untuk tipe rapot
    public function scopeEvaluasi($query)
    {
        return $query->where('tipe', 'evaluasi');
    }

    public function scopeStandar($query)
    {
        return $query->where('tipe', 'standar');
    }

    // Scope untuk status tertentu
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Attribute untuk format nilai
    public function getNilaiFormattedAttribute()
    {
        return number_format($this->nilai, 2);
    }

    public function getTotalJamFormattedAttribute()
    {
        return number_format($this->total_jam, 1) . ' jam';
    }

    // Attribute untuk status warna
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'published', 'selesai' => 'bg-green-100 text-green-800',
            'draft' => 'bg-gray-100 text-gray-800',
            'review' => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }

    // Attribute untuk tipe warna
    public function getTipeColorAttribute()
    {
        return match($this->tipe) {
            'evaluasi' => 'bg-purple-100 text-purple-800',
            'standar' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Hitung rata-rata jam per hari
    public function calculateAverageHours()
    {
        if ($this->hari_kerja > 0) {
            $this->rata_jam_perhari = round($this->total_jam / $this->hari_kerja, 2);
            $this->save();
        }
        return $this->rata_jam_perhari;
    }
}