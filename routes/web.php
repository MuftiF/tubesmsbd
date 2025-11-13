<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CleaningAbsenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login-pegawai', function () {
    return view('login-pegawai');
})->name('login.pegawai');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
});

require __DIR__.'/auth.php';

// Route untuk role-based dashboard
Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin']);

Route::get('security/dashboard', function () {
    return view('security.dashboard');
})->middleware(['auth', 'security']);

Route::get('manager/dashboard', function () {
    return view('manager.dashboard');
})->middleware(['auth', 'manager']);

Route::get('kantoran/dashboard', function () {
    return view('kantoran.dashboard');
})->middleware(['auth', 'kantoran']);

// GROUP ROUTE CLEANING SERVICE
Route::middleware(['auth', 'cleaning'])
    ->prefix('cleaning')
    ->name('cleaning.')
    ->group(function () {

        // Dashboard cleaning
        Route::get('/dashboard', [CleaningAbsenController::class, 'index'])->name('dashboard');
        
        // Absen
        Route::get('/absen', [CleaningAbsenController::class, 'index'])->name('absen');
        
        // Proses absen datang
        Route::post('/absen-datang', [CleaningAbsenController::class, 'absenDatang'])->name('absen.datang');
        
        // Proses absen pulang
        Route::post('/absen-pulang', [CleaningAbsenController::class, 'absenPulang'])->name('absen.pulang');
});
