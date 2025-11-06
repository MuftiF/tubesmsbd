<?php

namespace App\Http\Controllers;

use App\Models\RekapTahunan;
use Illuminate\Http\Request;

class RekapTahunanController extends Controller
{
    public function index(){return response()->json(RekapTahunan::all());}
    public function store(Request $r){return response()->json(RekapTahunan::create($r->all()),201);}
    public function show($id){return response()->json(RekapTahunan::findOrFail($id));}
    public function update(Request $r,$id){$d=RekapTahunan::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){RekapTahunan::destroy($id);return response()->json(['message'=>'Rekap tahunan dihapus']);}
}
