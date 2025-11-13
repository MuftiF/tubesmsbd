<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    // 🔹 Tampilkan semua data shift
    public function index()
    {
        return response()->json(Shift::all(), 200);
    }

    // 🔹 Simpan data shift baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $shift = Shift::create($validated);
        return response()->json($shift, 201);
    }

    // 🔹 Tampilkan satu data shift berdasarkan ID
    public function show($id)
    {
        $shift = Shift::findOrFail($id);
        return response()->json($shift, 200);
    }

    // 🔹 Update data shift berdasarkan ID
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->update($request->all());
        return response()->json($shift, 200);
    }

    // 🔹 Hapus data shift
    public function destroy($id)
    {
        Shift::destroy($id);
        return response()->json(['message' => 'Data shift berhasil dihapus'], 200);
    }
}
