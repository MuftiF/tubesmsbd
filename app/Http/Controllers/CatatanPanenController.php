<?php

namespace App\Http\Controllers;

use App\Models\CatatanPanen;
use Illuminate\Http\Request;

class CatatanPanenController extends Controller
{
    public function index(){return response()->json(CatatanPanen::all());}
   public function store(Request $r)
{
    $v = $r->validate([
        'id_pegawai'     => 'required|integer',
        'tanggal'        => 'required|date',
        'id_area_kerja'  => 'nullable|integer',
        'jumlah_tandan'  => 'required|integer',
        'berat_kg'       => 'required|numeric',
        'catatan'        => 'nullable|string|max:255',
    ]);

    $data = CatatanPanen::create($v);
    return response()->json($data, 201);
}
    public function show($id){return response()->json(CatatanPanen::findOrFail($id));}
    public function update(Request $r,$id){$d=CatatanPanen::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){CatatanPanen::destroy($id);return response()->json(['message'=>'Data panen dihapus']);}
}