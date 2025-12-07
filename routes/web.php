<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RapotController;
use App\Http\Controllers\AnnouncementController;

// ======================================================================
// DEBUG ROUTES - UNTUK TROUBLESHOOTING
// ======================================================================
Route::get('/debug-session', function() {
    return [
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'cookies' => request()->cookie(),
        'ip' => request()->ip(),
        'url' => request()->fullUrl()
    ];
});

Route::get('/debug-manager', [HomeController::class, 'managerDashboard']);
Route::get('/debug-test', function() {
    return 'Debug test page - no middleware';
});

// ======================================================================
// PUBLIC ROUTES
// ======================================================================
Route::get('/', fn() => view('welcome'));
Route::get('/home', fn() => view('home'))->name('home');
Route::get('/login-pegawai', fn() => view('login-pegawai'))->name('login.pegawai');

// ======================================================================
// AUTH ROUTES
// ======================================================================
require __DIR__.'/auth.php';

// ======================================================================
// ROUTES YANG MEMBUTUHKAN LOGIN (MIDDLEWARE AUTH)
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

    // RAPOT USER (UMUM UNTUK SEMUA USER)
    Route::get('/rapot', [RapotController::class, 'indexUser'])->name('rapot.user');

    // PENGUMUMAN USER (UMUM UNTUK SEMUA USER)
    Route::get('/pengumuman', [AnnouncementController::class, 'showToUsers'])
        ->name('pengumuman.user');
});

// ======================================================================
// USER (Pekerja) - DENGAN MIDDLEWARE ROLE
// ======================================================================
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/riwayat', [HomeController::class, 'userRiwayat'])->name('user.riwayat');
});

// ======================================================================
// SECURITY - DENGAN MIDDLEWARE ROLE
// ======================================================================
Route::prefix('security')->middleware(['auth', 'security'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'securityDashboard'])->name('security.dashboard');
});

// ======================================================================
// CLEANING - DENGAN MIDDLEWARE ROLE
// ======================================================================
Route::prefix('cleaning')->middleware(['auth', 'cleaning'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'cleaningDashboard'])->name('cleaning.dashboard');
});

// ======================================================================
// KANTORAN - DENGAN MIDDLEWARE ROLE
// ======================================================================
Route::prefix('kantoran')->middleware(['auth', 'kantoran'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'kantoranDashboard'])->name('kantoran.dashboard');
});

// ======================================================================
// MANAGER - DENGAN MIDDLEWARE AUTH + MANAGER
// ======================================================================
Route::prefix('manager')->middleware(['auth', 'manager'])->group(function () {
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
        
    // Pengumuman Manager
    Route::get('/pengumuman', [AnnouncementController::class, 'indexManager'])->name('manager.pengumuman.index');
    Route::post('/pengumuman', [AnnouncementController::class, 'storeManager'])->name('manager.pengumuman.store');
    Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroyManager'])->name('manager.pengumuman.delete');
});

// ======================================================================
// ADMIN - DENGAN MIDDLEWARE AUTH + ADMIN
// ======================================================================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/pegawai', [HomeController::class, 'kelolaPegawai'])->name('admin.pegawai');
    Route::get('/laporan', [HomeController::class, 'laporanAdmin'])->name('admin.laporan');

    // Rapot Admin
    Route::get('/rapot', [RapotController::class, 'indexAdmin'])->name('admin.rapot.index');
    Route::post('/rapot/generate/{user}', [RapotController::class, 'generate'])->name('admin.rapot.generate');
    Route::post('/rapot/generate/pdf/{id}', [RapotController::class, 'generatePDF'])->name('admin.rapot.generate.pdf');
    Route::post('/rapot/generate/excel/{id}', [RapotController::class, 'generateExcel'])->name('admin.rapot.generate.excel');

    // Pengumuman Admin
    Route::get('/pengumuman', [AnnouncementController::class, 'indexAdmin'])->name('admin.pengumuman');
    Route::post('/pengumuman', [AnnouncementController::class, 'store'])->name('admin.pengumuman.store');
    Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroy'])->name('admin.pengumuman.delete');
});