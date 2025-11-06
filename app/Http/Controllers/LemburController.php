<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    public function index(){return response()->json(Lembur::all());}
    public function store(Request $r){
        $v=$r->validate([
            'id_pegawai'=>'required|integer',
            'tanggal'=>'required|date',
            'jam_mulai'=>'required',
            'jam_selesai'=>'required',
            'keterangan'=>'nullable|string|max:255'
        ]);
        return response()->json(Lembur::create($v),201);
    }
    public function show($id){return response()->json(Lembur::findOrFail($id));}
    public function update(Request $r,$id){$d=Lembur::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){Lembur::destroy($id);return response()->json(['message'=>'Data lembur dihapus']);}
}
