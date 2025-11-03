<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

    $attendanceToday = Attendance::where('user_id', $user->id)
        ->whereDate('created_at', $today)
        ->first();

    // Statistik bulanan contoh
    $monthlyCount = Attendance::where('user_id', $user->id)
        ->whereMonth('created_at', now()->month)
        ->count();

    $onTimeCount = Attendance::where('user_id', $user->id)
        ->whereMonth('created_at', now()->month)
        ->where('status', 'tepat waktu')
        ->count();

    $averageHours = Attendance::where('user_id', $user->id)
        ->whereMonth('created_at', now()->month)
        ->whereNotNull('check_out')
        ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, check_in, check_out)) as avg_hours')
        ->value('avg_hours');

    return view('attendance', compact(
        'attendanceToday', 'monthlyCount', 'onTimeCount', 'averageHours'
    ));
    }

    public function store(Request $request)
    {
    $user = Auth::user();
    $today = \Carbon\Carbon::today();

    $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('created_at', $today)
        ->first();

    if (!$attendance) {
        // === Check In ===
        $photoPath = $request->file('photo')->store('absensi', 'public');
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'check_in' => now(),
            'status' => now()->format('H:i') <= '09:00' ? 'tepat waktu' : 'terlambat',
            'photo_path' => $photoPath,
            'note' => $request->note,
        ]);
        return back()->with('success', 'Check In berhasil!');
    } else {
        // === Check Out ===
        $attendance->update([
            'check_out' => now(),
        ]);
        return back()->with('success', 'Check Out berhasil!');
    }
    }

    public function history(Request $request)
    {
        $query = Attendance::where('user_id', Auth::id());
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        $attendances = $query->latest()->get();

        return response()->json($attendances);
    }
}
