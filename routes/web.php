<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RapotController;
use App\Http\Controllers\AnnouncementController;

// PUBLIC
Route::get('/', fn() => view('welcome'));
Route::get('/home', fn() => view('home'))->name('home');
Route::get('/login-pegawai', fn() => view('login-pegawai'))->name('login.pegawai');

// AUTH
require __DIR__.'/auth.php';

// DASHBOARD REDIRECT OTOMATIS BERDASARKAN ROLE
Route::middleware('auth')->get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// ======================================================================
// ROUTES YANG HANYA BOLEH DIAKSES JIKA LOGIN
// ======================================================================
Route::middleware('auth')->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ABSENSI
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    // USER (Pekerja)
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/riwayat', [HomeController::class, 'userRiwayat'])->name('user.riwayat');
    });

    // SECURITY
    Route::prefix('security')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'securityDashboard'])->name('security.dashboard');
    });

    // CLEANING
    Route::prefix('cleaning')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'cleaningDashboard'])->name('cleaning.dashboard');
    });

    // KANTORAN
    Route::prefix('kantoran')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'kantoranDashboard'])->name('kantoran.dashboard');
    });

    // ======================================================================
    // MANAGER (SESUAI DENGAN VIEW pegawai.blade.php)
    // ======================================================================
    Route::prefix('manager')->group(function () {

        Route::get('/dashboard', [HomeController::class, 'managerDashboard'])->name('manager.dashboard');
        Route::get('/laporan', [HomeController::class, 'laporanManager'])->name('manager.laporan');
        Route::get('/log', [HomeController::class, 'managerLog'])->name('manager.log');

        // Halaman lihat pegawai
        Route::get('/pegawai', [HomeController::class, 'managerPegawai'])->name('manager.pegawai');

        // Tambah pegawai (form add)
        Route::post('/pegawai', [HomeController::class, 'managerTambahPegawai'])
            ->name('manager.pegawai.tambah');

        // Update pegawai (modal edit)
        Route::put('/pegawai/{id}', [HomeController::class, 'managerUpdatePegawai'])
            ->name('manager.pegawai.update');

        // Hapus pegawai
        Route::delete('/pegawai/{id}', [HomeController::class, 'managerHapusPegawai'])
            ->name('manager.pegawai.hapus');
    });

    // ======================================================================
    // ADMIN
    // ======================================================================
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/pegawai', [HomeController::class, 'kelolaPegawai'])->name('admin.pegawai');
        Route::get('/laporan', [HomeController::class, 'laporanAdmin'])->name('admin.laporan');

        // Rapot Admin
        Route::get('/rapot', [RapotController::class, 'indexAdmin'])->name('admin.rapot.index');
        Route::post('/rapot/generate/{user}', [RapotController::class, 'generate'])->name('admin.rapot.generate');

        // Pengumuman Admin
        Route::get('/pengumuman', [AnnouncementController::class, 'indexAdmin'])->name('admin.pengumuman');
        Route::post('/pengumuman', [AnnouncementController::class, 'store'])->name('admin.pengumuman.store');
        Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroy'])->name('admin.pengumuman.delete');
    });

    // ======================================================================
    // RAPOT USER
    // ======================================================================
    Route::get('/rapot', [RapotController::class, 'indexUser'])->name('rapot.user');

    // ======================================================================
    // PENGUMUMAN USER
    // ======================================================================
    Route::get('/pengumuman', [AnnouncementController::class, 'showToUsers'])
        ->name('pengumuman.user');
});
