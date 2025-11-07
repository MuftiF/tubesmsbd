<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index(){return response()->json(Penggajian::with('pegawai')->get());}
    public function store(Request $r){
        $v=$r->validate(['id_pegawai'=>'required|integer','periode'=>'required|string','total_gaji'=>'required|numeric','catatan'=>'nullable|string']);
        return response()->json(Penggajian::create($v),201);
    }
    public function show($id){return response()->json(Penggajian::findOrFail($id));}
    public function update(Request $r,$id){$d=Penggajian::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){Penggajian::destroy($id);return response()->json(['message'=>'Data penggajian dihapus']);}
}
