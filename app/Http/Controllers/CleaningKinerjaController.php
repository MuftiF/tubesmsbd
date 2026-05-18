<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\KinerjaCleaning;
use Carbon\Carbon;

class CleaningKinerjaController extends Controller
{
    public function index()
    {
        // Ambil riwayat kinerja hari ini
        $riwayatHariIni = KinerjaCleaning::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Total semua kinerja user
        $totalKinerja = KinerjaCleaning::where('user_id', Auth::id())->count();
        
        // Total area unik yang pernah dikerjakan (opsional)
        $totalAreaHariIni = KinerjaCleaning::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->distinct('area')
            ->count('area');
        
        return view('cleaning.kinerja', compact('riwayatHariIni', 'totalKinerja', 'totalAreaHariIni'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'foto' => 'required|array',
            'foto.*' => 'required|string',
            'keterangan' => 'required|array',
            'keterangan.*' => 'required|string|min:3',
            'area' => 'nullable|array',
            'area.*' => 'nullable|string|max:255',
        ]);

        $savedCount = 0;

        foreach ($request->foto as $index => $fotoBase64) {
            // Skip jika foto kosong
            if (empty($fotoBase64)) {
                continue;
            }

            // Konversi base64 ke file
            $imageData = $this->convertBase64ToImage($fotoBase64);
            
            if (!$imageData) {
                continue;
            }

            // Generate nama file unik
            $fileName = 'kinerja-cleaning/' . date('Y/m/d') . '/' . uniqid() . '.jpg';
            
            // Simpan ke storage
            $saved = Storage::disk('public')->put($fileName, $imageData);
            
            if (!$saved) {
                continue;
            }

            // Simpan ke database dengan path yang benar
            KinerjaCleaning::create([
                'user_id' => Auth::id(),
                'area' => $request->area[$index] ?? 'Area ' . ($index + 1),
                'keterangan' => $request->keterangan[$index],
                'foto' => $fileName, // Simpan path relatif dari storage
                'tanggal' => Carbon::today()->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $savedCount++;
        }

        if ($savedCount === 0) {
            return back()->with('error', 'Tidak ada data yang berhasil disimpan. Pastikan foto sudah diambil.');
        }

        return back()->with('success', $savedCount . ' data kinerja berhasil disimpan.');
    }

    /**
     * Konversi base64 ke image binary
     */
    private function convertBase64ToImage($base64String)
    {
        // Cek apakah base64 valid
        if (empty($base64String)) {
            return null;
        }

        // Pisahkan metadata dari base64
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $imageType = strtolower($type[1]);
            
            // Validasi tipe gambar
            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return null;
            }
        }

        // Decode base64
        $imageData = base64_decode($base64String);
        
        if ($imageData === false) {
            return null;
        }

        return $imageData;
    }

    /**
     * API endpoint untuk riwayat (opsional, untuk AJAX)
     */
    public function getRiwayatHariIni()
    {
        $riwayat = KinerjaCleaning::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'area' => $item->area,
                    'keterangan' => $item->keterangan,
                    'foto' => asset('storage/' . $item->foto),
                    'waktu' => $item->created_at->format('H:i'),
                    'tanggal_formatted' => $item->created_at->format('d M Y - H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $riwayat,
            'total' => $riwayat->count()
        ]);
    }

    /**
     * Hapus data kinerja (opsional)
     */
    public function destroy($id)
    {
        $kinerja = KinerjaCleaning::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$kinerja) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Hapus file foto jika ada
        if ($kinerja->foto && Storage::disk('public')->exists($kinerja->foto)) {
            Storage::disk('public')->delete($kinerja->foto);
        }

        $kinerja->delete();

        return back()->with('success', 'Data kinerja berhasil dihapus');
    }
}