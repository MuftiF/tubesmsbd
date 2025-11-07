<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    public function index(){return response()->json(HariLibur::all());}
    public function store(Request $r){
        $v=$r->validate(['tanggal'=>'required|date','nama'=>'required|string|max:100','nasional'=>'boolean','catatan'=>'nullable|string|max:255']);
        return response()->json(HariLibur::create($v),201);
    }
    public function show($id){return response()->json(HariLibur::findOrFail($id));}
    public function update(Request $r,$id){$d=HariLibur::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){HariLibur::destroy($id);return response()->json(['message'=>'Hari libur dihapus']);}
}
