<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $data = Absensi::with([
            'pegawai:id,nama_lengkap,email,peran',
            'shift:id,nama_shift,jam_mulai,jam_selesai',
            'areaKerja:id,nama_area',
            'statusAbsensi:id,nama_status'
        ])->orderBy('tanggal_absen', 'desc')->get();

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'tanggal_absen' => 'required|date',
            'id_shift' => 'nullable|exists:shift,id',
            'id_area_kerja' => 'nullable|exists:area_kerja,id',
            'id_status_absensi' => 'nullable|exists:status_absensi,id',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i',
            'total_menit' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Cegah absen dua kali di tanggal yang sama
        $sudahAbsen = Absensi::where('id_pegawai', $validated['id_pegawai'])
            ->where('tanggal_absen', $validated['tanggal_absen'])
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'message' => 'Pegawai sudah melakukan absensi pada tanggal ini'
            ], 409);
        }

        $data = Absensi::create($validated);

        return response()->json([
            'message' => 'Data absensi berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // 🔹 Ambil detail absensi berdasarkan ID
    public function show($id)
    {
        $data = Absensi::with([
            'pegawai:id,nama_lengkap,email,peran',
            'shift:id,nama_shift,jam_mulai,jam_selesai',
            'areaKerja:id,nama_area',
            'statusAbsensi:id,nama_status'
        ])->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data absensi tidak ditemukan'], 404);
        }

        return response()->json($data, 200);
    }

    // 🔹 Update absensi
    public function update(Request $request, $id)
    {
        $data = Absensi::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data absensi tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'id_shift' => 'nullable|exists:shift,id',
            'id_area_kerja' => 'nullable|exists:area_kerja,id',
            'id_status_absensi' => 'nullable|exists:status_absensi,id',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i',
            'total_menit' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $data->update($validated);

        return response()->json([
            'message' => 'Data absensi berhasil diperbarui',
            'data' => $data
        ], 200);
    }

    // 🔹 Hapus absensi
    public function destroy($id)
    {
        $data = Absensi::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data absensi tidak ditemukan'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data absensi berhasil dihapus'], 200);
    }
}