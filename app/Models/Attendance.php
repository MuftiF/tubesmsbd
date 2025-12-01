<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'photos',        // penting
        'description',   // penting
        'note'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'photos' => 'array',   // WAJIB biar JSON jadi array
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
