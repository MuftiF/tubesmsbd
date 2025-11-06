<?php

namespace App\Http\Controllers;

use App\Models\AreaKerja;
use Illuminate\Http\Request;

class AreaKerjaController extends Controller
{
    public function index(){return response()->json(AreaKerja::all());}
    public function store(Request $r){
        $v=$r->validate(['nama'=>'required|string|max:100','lokasi'=>'nullable|string|max:255','keterangan'=>'nullable|string|max:255']);
        return response()->json(AreaKerja::create($v),201);
    }
    public function show($id){return response()->json(AreaKerja::findOrFail($id));}
    public function update(Request $r,$id){$d=AreaKerja::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){AreaKerja::destroy($id);return response()->json(['message'=>'Data area kerja dihapus']);}
}
