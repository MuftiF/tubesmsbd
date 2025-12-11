<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SheetAbsenAggregateExport implements FromView
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function view(): View
    {
        // Generate tanggal
        $dates = collect();
        $start = Carbon::parse($this->from);
        $end   = Carbon::parse($this->to);

        while($start->lte($end)) {
            $dates->push($start->format('Y-m-d'));
            $start->addDay();
        }

        // Semua pegawai kecuali admin
        $users = DB::table('users')
            ->whereIn('role', ['user','security','cleaning','kantoran'])
            ->orderBy('id')
            ->get();

        // Absensi
        $absensi = DB::table('attendances')
            ->whereBetween('date', [$this->from, $this->to])
            ->select('user_id', 'date', 'status', 'check_in')
            ->get()
            ->groupBy('user_id');

        // Panen
        $panen = DB::table('catatan_panen')
            ->whereBetween('tanggal', [$this->from, $this->to])
            ->select('id_pegawai', 'tanggal', 'jumlah_tandan', 'berat_kg')
            ->get()
            ->mapToGroups(function ($item) {
                return [$item->id_pegawai => [
                    'tanggal'        => $item->tanggal,
                    'jumlah_tandan'  => (int) ($item->jumlah_tandan ?? 0),
                    'berat_kg'       => (float) ($item->berat_kg ?? 0),
                ]];
            });

        return view('exports.sheet_aggregate', [
            'users'   => $users,
            'dates'   => $dates,
            'absensi' => $absensi,
            'panen'   => $panen
        ]);
    }
}
