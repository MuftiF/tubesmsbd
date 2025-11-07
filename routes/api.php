<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AreaKerjaController;
use App\Http\Controllers\CatatanPanenController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\RekapBulananController;
use App\Http\Controllers\RekapHarianController;
use App\Http\Controllers\RekapTahunanController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StatusAbsensiController;

Route::apiResource('absensi', AbsensiController::class);

Route::apiResource('area-kerja', AreaKerjaController::class);

Route::apiResource('rekap-harian', RekapHarianController::class);

Route::apiResource('rekap-bulanan', RekapBulananController::class);

Route::apiResource('rekap-tahunan', RekapTahunanController::class);

Route::apiResource('shift', ShiftController::class);

Route::apiResource('status-absensi', StatusAbsensiController::class);

Route::apiResource('lembur', LemburController::class);

Route::apiResource('log-aktivitas', LogAktivitasController::class);

Route::apiResource('pegawai', PegawaiController::class);

Route::apiResource('departemen', DepartemenController::class);

Route::apiResource('hari-libur', HariLiburController::class);

Route::apiResource('catatan-panen', CatatanPanenController::class);

Route::apiResource('penggajian', PenggajianController::class);


Route::get('/test', function () {
    return response()->json(['message' => 'API aktif dan berjalan 🚀']);
});
