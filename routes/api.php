<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatatanPanenController;
use App\Http\Controllers\StatusAbsensiController;

Route::apiResource('status-absensi', StatusAbsensiController::class);

Route::apiResource('catatan-panen', CatatanPanenController::class);


Route::get('/test', function () {
    return response()->json(['message' => 'API aktif dan berjalan 🚀']);
});
