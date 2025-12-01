<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Halaman utama absensi.
     */
    public function index()
    {
        // Cari absensi hari ini untuk user yang sedang login
        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->first();

        return view('attendance.index', compact('absenHariIni'));
    }

    /**
     * Proses Check-in & Check-out.
     */
    public function store(Request $request)
    {
        // Validasi foto multiple + deskripsi opsional
        $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:4096',
            'description' => 'nullable|string|max:500'
        ]);

        // Ambil absensi hari ini
        $absen = Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->first();

        // Kalau belum ada, berarti ini CHECK IN
        if (!$absen) {
            $absen = new Attendance();
            $absen->user_id = Auth::id();
            $absen->check_in = now();
        } else {
            // Kalau sudah check-in, berarti ini CHECK OUT
            $absen->check_out = now();
        }

        // Simpan foto multiple (jika ada)
        $photosPath = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $photosPath[] = $file->store('attendance_photos', 'public');
            }
        }

        // Kalau foto ada, simpan JSON
        if (!empty($photosPath)) {
            $absen->photos = json_encode($photosPath);
        }

        // Simpan deskripsi jika ada
        if ($request->description) {
            $absen->description = $request->description;
        }

        $absen->save();

        return redirect()->route('security.dashboard')->with('success', 'Absensi berhasil dicatat.');
    }

    /**
     * Riwayat absensi.
     */
    public function history()
    {
        $riwayat = Attendance::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attendance.history', compact('riwayat'));
    }
}
