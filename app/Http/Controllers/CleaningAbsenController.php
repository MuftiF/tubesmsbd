<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CleaningAbsenController extends Controller
{
    public function index()
    {
        // Contoh logika sederhana:
        // Cek apakah user sudah absen datang hari ini
        $sudahHadir = session()->has('absen_datang'); // bisa diganti dengan query DB nanti

        return view('cleaning.dashboard', compact('sudahHadir'));
    }

    public function absenDatang(Request $request)
    {
        $request->validate([
            'foto_datang' => 'required|image|max:2048',
        ]);

        $path = $request->file('foto_datang')->store('absen/datang', 'public');

        // Simpan data ke DB (sementara simpan ke session dulu)
        session(['absen_datang' => [
            'user_id' => auth()->id(),
            'foto_datang' => $path,
            'waktu_datang' => now(),
        ]]);

        return redirect()->route('cleaning.dashboard')->with('success', 'Absen datang berhasil!');
    }

    public function absenPulang(Request $request)
    {
        $request->validate([
            'hasil_kerja' => 'required|string',
            'foto_sebelum' => 'required|image|max:2048',
            'foto_sesudah' => 'required|image|max:2048',
        ]);

        $fotoSebelum = $request->file('foto_sebelum')->store('absen/sebelum', 'public');
        $fotoSesudah = $request->file('foto_sesudah')->store('absen/sesudah', 'public');

        // Simpan ke database nantinya, untuk sekarang hapus session
        session()->forget('absen_datang');

        return redirect()->route('cleaning.absen')->with('success', 'Absen pulang dan laporan berhasil dikirim!');
    }
}
