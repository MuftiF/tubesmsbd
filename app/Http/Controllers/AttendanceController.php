<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CatatanPanen;

class AttendanceController extends Controller
{
    // app/Http/Controllers/AttendanceController.php

// app/Http/Controllers/AttendanceController.php

public function index()
{
    $user = Auth::user();
    $today = Carbon::today('Asia/Jakarta');

    // 1. Ambil absensi hari ini (tetap dari tabel 'attendances')
    $attendanceToday = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->first();

    // 2. Hitung total kehadiran (tetap dari tabel 'attendances')
    $monthlyCount = Attendance::where('user_id', $user->id)
        ->whereMonth('date', Carbon::now('Asia/Jakarta')->month)
        ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
        ->count();

    // Inisialisasi variabel panen
    $monthlyPalmWeight = 0.0;
    $averageDailyPalmWeight = 0.0;
    
    // Inisialisasi data panen hari ini (untuk Check Out Box di view)
    $todayPalmWeight = 0.0; 

    // 3. Hanya hitung data panen jika role adalah 'user'
    if ($user->role == 'user') {
        $currentMonth = Carbon::now('Asia/Jakarta')->month;
        $currentYear = Carbon::now('Asia/Jakarta')->year;
        $userId = $user->id;

        // A. Total berat sawit bulan ini (DARI CATATANPANEN)
        $monthlyPalmWeight = CatatanPanen::where('id_pegawai', $userId)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('berat_kg') ?? 0.0; // <-- Mengambil SUM dari 'berat_kg' di CatatanPanen
            
        // B. Ambil data panen hari ini (DARI CATATANPANEN)
        $panenToday = CatatanPanen::where('id_pegawai', $userId)
            ->whereDate('tanggal', $today)
            ->first();
            
        if ($panenToday) {
            $todayPalmWeight = $panenToday->berat_kg;
        }

        // C. Hitung Rata-rata per Hari
        // Penting: Rata-rata dihitung berdasarkan jumlah kehadiran (monthlyCount)
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
        'todayPalmWeight', // <-- VARIABEL BARU UNTUK BERAT HARI INI
        'serverTime'
    ));
}
    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');

        // Cek apakah sudah ada absensi hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            // === Check In (Masuk) - Tanpa foto, hanya tombol ===
            // Gunakan timezone Asia/Jakarta
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

    // Validasi sesuai role
    if ($user->role == 'user') {
        $request->validate([
            'checkout_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'palm_weight' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);
    } elseif (in_array($user->role, ['security', 'cleaning', 'kantoran'])) {
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
        return back()->with('error', 'Anda sudah melakukan check out hari ini!');
    }

    $updateData = ['check_out' => Carbon::now('Asia/Jakarta')];

    // Role: pekerja sawit
    if ($user->role == 'user') {
        $checkoutPhotoPath = $request->file('checkout_photo')->store('checkout_photos', 'public');

        $updateData['checkout_photo_path'] = $checkoutPhotoPath;
        $updateData['palm_weight'] = $request->palm_weight;
        $updateData['note'] = $request->note;

        // Simpan catatan panen
        CatatanPanen::create([
            'id_pegawai'    => $user->id,
            'tanggal'       => $today->toDateString(),
            'id_area_kerja' => $user->id_area_kerja ?? null,
            'jumlah_tandan' => 0,
            'berat_kg'      => $request->palm_weight,
            'catatan'       => $request->note,
        ]);
    }
    // Role lain
    elseif (in_array($user->role, ['security', 'cleaning', 'kantoran'])) {
        $checkoutPhotoPath = $request->file('checkout_photo')->store('checkout_photos', 'public');
        $updateData['checkout_photo_path'] = $checkoutPhotoPath;
        $updateData['note'] = $request->note;
    }

    $attendance->update($updateData);

    return back()->with('success', 'Check Out berhasil! Terima kasih atas kerja keras Anda hari ini! 🎉');
}
     public function history()
    {
        $user = Auth::user();

        // Jika user adalah manager, redirect ke halaman log manager
        if ($user->role == 'manager') {
            return redirect()->route('manager.log');
        }

        // Untuk role lainnya, tampilkan riwayat absensi pribadi
        $riwayat = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('attendance.history', compact('riwayat'));
    }
}
