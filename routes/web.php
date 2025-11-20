<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CleaningAbsenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/login-pegawai', function () {
    return view('login-pegawai');
})->name('login.pegawai');

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated Routes - Common
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attendance Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
});

// Dashboard utama - redirect berdasarkan role
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Role-based Dashboard Routes - SEMENTARA TANPA MIDDLEWARE ROLE
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/pegawai', [HomeController::class, 'kelolaPegawai'])->name('admin.pegawai');
    Route::get('/laporan', [HomeController::class, 'laporanAdmin'])->name('admin.laporan');
});

Route::middleware(['auth'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'managerDashboard'])->name('manager.dashboard');
    Route::get('/laporan', [HomeController::class, 'laporanManager'])->name('manager.laporan');
    Route::get('/log', [HomeController::class, 'managerLog'])->name('manager.log');

    // Tambahkan routes CRUD untuk manager
    Route::get('/pegawai', [HomeController::class, 'managerPegawai'])->name('manager.pegawai');
    Route::post('/pegawai', [HomeController::class, 'managerTambahPegawai'])->name('manager.pegawai.tambah');
    Route::put('/pegawai/{id}', [HomeController::class, 'managerUpdatePegawai'])->name('manager.pegawai.update');
    Route::delete('/pegawai/{id}', [HomeController::class, 'managerHapusPegawai'])->name('manager.pegawai.hapus');
});

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/riwayat', [HomeController::class, 'userRiwayat'])->name('user.riwayat');
});

Route::middleware(['auth'])->prefix('security')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'securityDashboard'])->name('security.dashboard');
});

Route::middleware(['auth'])->prefix('cleaning')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'cleaningDashboard'])->name('cleaning.dashboard');
});

Route::middleware(['auth'])->prefix('kantoran')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'kantoranDashboard'])->name('kantoran.dashboard');
});

Route::middleware(['auth'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'managerDashboard'])->name('manager.dashboard');
    Route::get('/laporan', [HomeController::class, 'laporanManager'])->name('manager.laporan');
    Route::get('/log', [HomeController::class, 'managerLog'])->name('manager.log'); // Tambah ini
});

// Attendance routes untuk semua role kecuali admin
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
});
