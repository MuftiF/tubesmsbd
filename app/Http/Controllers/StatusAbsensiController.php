<?php

namespace App\Http\Controllers;

use App\Models\StatusAbsensi;
use Illuminate\Http\Request;

class StatusAbsensiController extends Controller
{
    public function index(){return response()->json(StatusAbsensi::all());}
    public function store(Request $r){
        $v=$r->validate(['nama'=>'required|string|max:50','keterangan'=>'nullable|string|max:255']);
        return response()->json(StatusAbsensi::create($v),201);
    }
    public function show($id){return response()->json(StatusAbsensi::findOrFail($id));}
    public function update(Request $r,$id){$d=StatusAbsensi::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){StatusAbsensi::destroy($id);return response()->json(['message'=>'Data dihapus']);}
}
