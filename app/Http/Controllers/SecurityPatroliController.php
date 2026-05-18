<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\PatroliSecurity;

class SecurityPatroliController extends Controller
{
    public function index()
    {
        $riwayatHariIni = PatroliSecurity::where('user_id', Auth::id())
            ->whereDate('waktu_patroli', today())
            ->latest()
            ->get();

        return view('security.patroli', compact('riwayatHariIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required',
            'keterangan' => 'nullable',
            'foto' => 'required',
        ]);

        $image = $request->foto;

        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        // folder tujuan
        $folderPath = public_path('uploads/patroli');

        // buat folder jika belum ada
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $imageName = 'patroli_' . time() . '.jpg';

        // simpan gambar
        File::put(
            $folderPath . '/' . $imageName,
            base64_decode($image)
        );

        PatroliSecurity::create([
            'user_id' => Auth::id(),
            'nama_area' => $request->nama_area,
            'keterangan' => $request->keterangan,
            'foto' => 'uploads/patroli/' . $imageName,
            'waktu_patroli' => now(),
        ]);

        return back()->with('success', 'Bukti patroli berhasil dikirim');
    }
}