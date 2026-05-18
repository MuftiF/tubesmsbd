<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KinerjaCleaning extends Model
{
    protected $fillable = [
        'user_id',
        'area',
        'keterangan',
        'foto',
        'tanggal',
    ];
}