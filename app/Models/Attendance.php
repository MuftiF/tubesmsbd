<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'photo_path',
        'checkout_photo_path',
        'palm_weight',
        'note'
    ];

    protected $casts = [
        'date' => 'date', //ini yang beda
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    // Relationship dengan user/pegawai
    public function user()
    {
        return $this->belongsTo(Pegawai::class, 'user_id');
    }
}
