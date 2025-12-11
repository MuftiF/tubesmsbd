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
        // RANGE DEFAULT: ambil tanggal paling awal & paling akhir
        $minAbs   = DB::table('attendances')->min('date');
        $maxAbs   = DB::table('attendances')->max('date');

        $minPanen = DB::table('catatan_panen')->min('tanggal');
        $maxPanen = DB::table('catatan_panen')->max('tanggal');

        if (!$this->from || !$this->to) {
            $this->from = min($minAbs, $minPanen);
            $this->to   = max($maxAbs, $maxPanen);
        }

        $from = Carbon::parse($this->from);
        $to   = Carbon::parse($this->to);

        // GENERATE LIST TANGGAL
        $dates = [];
        $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());

        foreach ($period as $d) {
            $dates[] = $d->format('Y-m-d');
        }

        // DATA PEGAWAI
        $users = DB::table('users')
            ->orderBy('id')
            ->get();

        // ABSENSI — group by user_id + tanggal
        $absensi = DB::table('attendances')
            ->whereBetween('date', [$this->from, $this->to])
            ->select('user_id', 'date', 'status', 'check_in', 'check_out')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_id . '-' . $item->date;
            });

        // PANEN — group by user_id + tanggal
        $panen = DB::table('catatan_panen')
            ->whereBetween('tanggal', [$this->from, $this->to])
            ->select('id_pegawai', 'tanggal', 'jumlah_tandan', 'berat_kg')
            ->get()
            ->groupBy(function ($item) {
                return $item->id_pegawai . '-' . $item->tanggal;
            });

        return view('exports.sheet_aggregate', [
            'dates' => $dates,
            'users' => $users,
            'absensi' => $absensi,
            'panen' => $panen
        ]);
    }
}
