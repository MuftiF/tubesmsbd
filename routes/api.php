<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\AbsensiController;
use app\Http\Controllers\AreaKerjaController;
use app\Http\Controllers\AttendanceController;
use app\Http\Controllers\CatatanPanenController;
use app\Http\Controllers\DepartemenController;
use app\Http\Controllers\HariLiburController;
use app\Http\Controllers\LemburController;
use app\Http\Controllers\LogAktivitasController;
use app\Http\Controllers\PegawaiController;
use app\Http\Controllers\PenggajianController;
use app\Http\Controllers\RekapBulananController;
use app\Http\Controllers\RekapHarianController;
use app\Http\Controllers\RekapTahunanController;
use app\Http\Controllers\ShiftController;
use app\Http\Controllers\StatusAbsensiController;

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

Route::apiResource('attendance', AttendanceController::class);



Route::get('/test', function () {
    return response()->json(['message' => 'API aktif dan berjalan 🚀']);
});
