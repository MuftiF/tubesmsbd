<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'judul',
        'isi',
        'tanggal',   // hapus kalau ga ada
    ];
}
