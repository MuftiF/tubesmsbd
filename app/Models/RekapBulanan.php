<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapHarian extends Model
{
    use HasFactory;
    protected $table = 'rekap_harian';
}

class RekapBulanan extends Model
{
    use HasFactory;
    protected $table = 'rekap_bulanan';
}

class RekapTahunan extends Model
{
    use HasFactory;
    protected $table = 'rekap_tahunan';
}

