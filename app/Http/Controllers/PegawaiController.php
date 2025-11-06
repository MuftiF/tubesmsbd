<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all(['id', 'nama_lengkap', 'email', 'peran', 'created_at', 'updated_at']);
        return response()->json($pegawai, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:pegawai,email',
            'kata_sandi' => 'required|string|min:6',
            'peran' => 'nullable|string|max:50',
        ]);

        // Enkripsi kata sandi sebelum disimpan
        $validated['kata_sandi'] = bcrypt($validated['kata_sandi']);

        $pegawai = Pegawai::create($validated);

        // Hilangkan kata_sandi dari response
        unset($pegawai['kata_sandi']);

        return response()->json([
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => $pegawai
        ], 201);
    }

    public function show($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        unset($pegawai['kata_sandi']); // hilangkan kata sandi dari response
        return response()->json($pegawai, 200);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama_lengkap' => 'sometimes|required|string|max:100',
            'email' => ['sometimes', 'required', 'email', Rule::unique('pegawai')->ignore($pegawai->id)],
            'kata_sandi' => 'nullable|string|min:6',
            'peran' => 'nullable|string|max:50',
        ]);

        if (isset($validated['kata_sandi'])) {
            $validated['kata_sandi'] = bcrypt($validated['kata_sandi']);
        }

        $pegawai->update($validated);

        unset($pegawai['kata_sandi']);

        return response()->json([
            'message' => 'Data pegawai berhasil diperbarui',
            'data' => $pegawai
        ], 200);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $pegawai->delete();

        return response()->json(['message' => 'Data pegawai berhasil dihapus'], 200);
    }
}
