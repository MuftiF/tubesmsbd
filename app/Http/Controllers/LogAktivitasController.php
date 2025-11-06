<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(){return response()->json(LogAktivitas::all());}
    public function store(Request $r){$v=$r->validate(['id_pegawai'=>'required|integer','aktivitas'=>'required|string','waktu'=>'nullable|datetime']);return response()->json(LogAktivitas::create($v),201);}
    public function show($id){return response()->json(LogAktivitas::findOrFail($id));}
    public function update(Request $r,$id){$d=LogAktivitas::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){LogAktivitas::destroy($id);return response()->json(['message'=>'Log aktivitas dihapus']);}
}
