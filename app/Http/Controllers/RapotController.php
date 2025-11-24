<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rapot;
use App\Models\Attendance;
use Carbon\Carbon;

class RapotController extends Controller
{
    // Halaman admin
    public function indexAdmin()
    {
        $users = User::whereIn('role', ['security', 'cleaning', 'kantoran', 'user'])->get();

        return view('rapot.admin.admin', compact('users'));
    }

    // Generate rapot
    public function generate(User $user)
    {
        $start = Carbon::now()->subDays(30)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $absen = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->get();

        $totalJam = 0;

        foreach ($absen as $a) {
            if ($a->check_in && $a->check_out) {
                $in = Carbon::parse($a->check_in);
                $out = Carbon::parse($a->check_out);
                $totalJam += $out->diffInHours($in);
            }
        }

        Rapot::create([
            'id_user' => $user->id,
            'periode' => $start->format('d M') . ' - ' . $end->format('d M'),
            'nilai' => $totalJam,
            'catatan' => "Total jam kerja 30 hari terakhir.",
        ]);

        return back()->with('success', 'Rapot untuk ' . $user->name . ' berhasil dibuat.');
    }

    // Halaman rapot user
    public function indexUser()
    {
        $rapots = Rapot::where('id_user', auth()->id())->get();

        return view('rapot.user', compact('rapots'));
    }
}
