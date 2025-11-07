<?php

namespace App\Http\Controllers;

use App\Models\RekapBulanan;
use Illuminate\Http\Request;

class RekapBulananController extends Controller
{
    public function index(){return response()->json(RekapBulanan::all());}
    public function store(Request $r){return response()->json(RekapBulanan::create($r->all()),201);}
    public function show($id){return response()->json(RekapBulanan::findOrFail($id));}
    public function update(Request $r,$id){$d=RekapBulanan::findOrFail($id);$d->update($r->all());return response()->json($d);}
    public function destroy($id){RekapBulanan::destroy($id);return response()->json(['message'=>'Rekap bulanan dihapus']);}
}
