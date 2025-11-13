<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    // Tampilkan semua data departemen
    public function index()
    {
        return response()->json(Departemen::all(), 200);
    }

    // Simpan data departemen baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $departemen = Departemen::create($validated);
        return response()->json($departemen, 201);
    }

    // Tampilkan 1 data berdasarkan ID
    public function show($id)
    {
        $departemen = Departemen::findOrFail($id);
        return response()->json($departemen, 200);
    }

    // Update data departemen
    public function update(Request $request, $id)
    {
        $departemen = Departemen::findOrFail($id);
        $departemen->update($request->all());
        return response()->json($departemen, 200);
    }

    // Hapus data departemen
    public function destroy($id)
    {
        Departemen::destroy($id);
        return response()->json(['message' => 'Data departemen dihapus'], 200);
    }
}
