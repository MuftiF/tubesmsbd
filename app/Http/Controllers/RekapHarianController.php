<?php

namespace App\Http\Controllers;

use App\Models\RekapHarian;
use Illuminate\Http\Request;

class RekapHarianController extends Controller
{
    public function index(){return response()->json(RekapHarian::all());}
    public function store(Request $r){return response()->json(RekapHarian::create($r->all()),201);}
    public function show($id){return response()->json(RekapHarian::findOrFail($id));}
    public function update(Request $r,$id){$d=RekapHarian::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){RekapHarian::destroy($id);return response()->json(['message'=>'Rekap harian dihapus']);}
}
