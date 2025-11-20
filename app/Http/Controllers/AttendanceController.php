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
        $today = Carbon::today('Asia/Jakarta');

        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $monthlyCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now('Asia/Jakarta')->month)
            ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
            ->count();

        // Total berat sawit bulan ini (hanya untuk role user/pekerja sawit)
        $monthlyPalmWeight = 0;
        if ($user->role == 'user') {
            $monthlyPalmWeight = Attendance::where('user_id', $user->id)
                ->whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                ->sum('palm_weight') ?? 0;
        }

        $serverTime = Carbon::now('Asia/Jakarta');

        return view('attendance.index', compact(
            'attendanceToday',
            'monthlyCount',
            'monthlyPalmWeight',
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

        // Validasi berbeda berdasarkan role
        if ($user->role == 'user') {
            // Pekerja sawit: foto + berat sawit + catatan opsional
            $request->validate([
                'checkout_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'palm_weight' => 'required|numeric|min:0',
                'note' => 'nullable|string|max:500',
            ]);
        } elseif (in_array($user->role, ['security', 'cleaning', 'kantoran'])) {
            // Security, Cleaning, Kantoran: foto + deskripsi pekerjaan
            $request->validate([
                'checkout_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'note' => 'required|string|max:500',
            ], [
                'note.required' => 'Deskripsi pekerjaan harus diisi',
            ]);
        }
        // Admin & Manager: tidak perlu validasi (hanya tombol)

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

        $updateData = [
            'check_out' => Carbon::now('Asia/Jakarta'),
        ];

        // Role user: upload foto + berat sawit
        if ($user->role == 'user') {
            $checkoutPhotoPath = $request->file('checkout_photo')->store('checkout_photos', 'public');
            $updateData['checkout_photo_path'] = $checkoutPhotoPath;
            $updateData['palm_weight'] = $request->palm_weight;
            $updateData['note'] = $request->note;
        }
        // Role security, cleaning, kantoran: upload foto + deskripsi pekerjaan
        elseif (in_array($user->role, ['security', 'cleaning', 'kantoran'])) {
            $checkoutPhotoPath = $request->file('checkout_photo')->store('checkout_photos', 'public');
            $updateData['checkout_photo_path'] = $checkoutPhotoPath;
            $updateData['note'] = $request->note;
        }
        // Role admin & manager: tidak ada foto dan deskripsi

        // Update attendance
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
