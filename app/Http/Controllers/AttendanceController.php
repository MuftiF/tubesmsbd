<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\CatatanPanen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');

        // Ambil absensi hari ini
        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Hitung total kehadiran dalam bulan berjalan
        $monthlyCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now('Asia/Jakarta')->month)
            ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
            ->count();

        // Default nilai panen
        $monthlyPalmWeight = 0.0;
        $averageDailyPalmWeight = 0.0;
        $todayPalmWeight = 0.0;

        // Hanya pekerja sawit yang memiliki panen
        if ($user->role == 'user') {

            $currentMonth = Carbon::now('Asia/Jakarta')->month;
            $currentYear = Carbon::now('Asia/Jakarta')->year;

            // Total sawit bulan ini
            $monthlyPalmWeight = CatatanPanen::where('id_pegawai', $user->id)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->sum('berat_kg') ?? 0.0;

            // Berat sawit hari ini
            $todayPalmWeight = CatatanPanen::where('id_pegawai', $user->id)
                ->whereDate('tanggal', $today)
                ->sum('berat_kg');

            // Rata-rata per hari (berdasarkan absen)
            if ($monthlyCount > 0 && $monthlyPalmWeight > 0) {
                $averageDailyPalmWeight = $monthlyPalmWeight / $monthlyCount;
            }
        }

        $serverTime = Carbon::now('Asia/Jakarta');

        return view('attendance.index', compact(
            'attendanceToday',
            'monthlyCount',
            'monthlyPalmWeight',
            'averageDailyPalmWeight',
            'todayPalmWeight',
            'serverTime'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            $checkInTime = Carbon::now('Asia/Jakarta');
            $status = $checkInTime->format('H:i') <= '07:00' ? 'tepat waktu' : 'terlambat';

            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in' => $checkInTime,
                'status' => $status,
            ]);

            return back()->with('success', 'Check In berhasil! Selamat bekerja! 🌴');
        }

        return back()->with('error', 'Anda sudah melakukan check in hari ini!');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        // Validasi khusus role
        if ($user->role == 'user') {
            $request->validate([
                'checkout_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'palm_weight' => 'required|numeric|min:0',
                'note' => 'nullable|string|max:500',
            ]);
        } else {
            $request->validate([
                'checkout_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'note' => 'required|string|max:500',
            ]);
        }

        $today = Carbon::today('Asia/Jakarta');

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan check in hari ini!');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah check out hari ini!');
        }

        $updateData = ['check_out' => Carbon::now('Asia/Jakarta')];

        // Simpan foto checkout
        $checkoutPhotoPath = $request->file('checkout_photo')->store('checkout_photos', 'public');
        $updateData['checkout_photo_path'] = $checkoutPhotoPath;

        // Jika pekerja sawit
        if ($user->role == 'user') {
            $updateData['palm_weight'] = $request->palm_weight;
            $updateData['note'] = $request->note;

            // Catat panen (DATA YANG DIGUNAKAN DASHBOARD)
            CatatanPanen::create([
                'id_pegawai' => $user->id,
                'tanggal' => $today->toDateString(),
                'id_area_kerja' => $user->id_area_kerja ?? null,
                'jumlah_tandan' => 0,
                'berat_kg' => $request->palm_weight,
                'catatan' => $request->note,
            ]);
        } else {
            $updateData['note'] = $request->note;
        }

        $attendance->update($updateData);

        return back()->with('success', 'Check Out berhasil! Terima kasih atas kerja keras Anda hari ini! 🎉');
    }

    public function history()
    {
        $user = Auth::user();

        if ($user->role == 'manager') {
            return redirect()->route('manager.log');
        }

        $riwayat = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('attendance.history', compact('riwayat'));
    }
}
