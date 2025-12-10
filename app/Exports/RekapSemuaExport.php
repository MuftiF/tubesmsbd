<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class RekapSemuaExport implements FromView
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to   = $to;
    }

   public function view(): View
{
    // Range default (semua tanggal tersimpan)
    if (!$this->from || !$this->to) {
    $minAbs = DB::table('attendances')->min('date');
    $maxAbs = DB::table('attendances')->max('date');

    $minPanen = DB::table('catatan_panen')->min('tanggal');
    $maxPanen = DB::table('catatan_panen')->max('tanggal');

    $this->from = min($minAbs, $minPanen);
    $this->to   = max($maxAbs, $maxPanen);
}


    $from = Carbon::parse($this->from);
    $to   = Carbon::parse($this->to);

    // Buat list tanggal yang urut ASC
    $dates = [];
    $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());
    foreach ($period as $d) {
        $dates[] = $d->format('Y-m-d');
    }

    // Semua pegawai
    $users = DB::table('users')
        ->whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
        ->orderBy('id')
        ->get();

    // Absensi PER TANGGAL
    $absensi = DB::table('attendances')
    ->whereBetween('date', [$this->from, $this->to])
    ->select('user_id', 'date', 'status', 'check_in')
    ->get()
    ->groupBy('user_id');


   // Panen dalam range: DIJUMLAH per pegawai per tanggal
$panen = DB::table('catatan_panen')
    ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
    ->select(
        'id_pegawai',
        'tanggal',
        DB::raw('COALESCE(SUM(jumlah_tandan), 0) as jumlah_tandan'),
        DB::raw('COALESCE(SUM(berat_kg), 0) as berat_kg')
    )
    ->groupBy('id_pegawai', 'tanggal')
    ->get()
    ->mapToGroups(function ($item) {
        return [
            $item->id_pegawai => [
                'tanggal'        => $item->tanggal,
                'jumlah_tandan'  => (int) ($item->jumlah_tandan ?? 0),
                'berat_kg'       => (float) ($item->berat_kg ?? 0),
            ]
        ];
    }); 


    return view('exports.sheet_aggregate', [
        'dates'   => $dates,
        'users'   => $users,
        'absensi' => $absensi,
        'panen'   => $panen,
    ]);
}

}
