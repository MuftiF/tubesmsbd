<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SheetAbsenExport implements FromView
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
        $dates = \Carbon\CarbonPeriod::create($this->from, $this->to)
            ->map(fn($d) => $d->toDateString())
            ->toArray();

        $users = User::whereIn('role', ['user','security','cleaning','kantoran'])
            ->orderBy('name')
            ->get();

        $attendance = Attendance::whereBetween('date', [$this->from, $this->to])
            ->get()
            ->groupBy('user_id')
            ->map(fn($items) => $items->keyBy('date')
                ->map(fn($i) => $i->status ?? '-')
                ->toArray()
            );

        return view('exports.sheet_absen', compact('users','dates','attendance'));
    }
}
