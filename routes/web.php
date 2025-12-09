<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RapotController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;


// ======================================================================
// PUBLIC ROUTES
// ======================================================================
Route::get('/', fn() => view('welcome'));
Route::get('/home', fn() => view('home'))->name('home');
Route::get('/login-pegawai', fn() => view('login-pegawai'))->name('login.pegawai');


// ======================================================================
// AUTH ROUTES (GUEST)
// ======================================================================
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});


// ======================================================================
// AUTH ROUTES (AUTHENTICATED)
// ======================================================================
Route::middleware('auth')->group(function () {

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard otomatis berdasarkan role
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');


    // ======================================================================
    // PROFILE
    // ======================================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ======================================================================
    // ATTENDANCE
    // ======================================================================
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');


    // ======================================================================
    // USER ROUTES
    // ======================================================================
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/riwayat', [HomeController::class, 'userRiwayat'])->name('user.riwayat');
    });

    Route::prefix('security')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'securityDashboard'])->name('security.dashboard');
    });

    Route::prefix('cleaning')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'cleaningDashboard'])->name('cleaning.dashboard');
    });

    Route::prefix('kantoran')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'kantoranDashboard'])->name('kantoran.dashboard');
    });


    // ======================================================================
    // MANAGER ROUTES
    // ======================================================================
    Route::prefix('manager')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'managerDashboard'])->name('manager.dashboard');
        Route::get('/laporan', [HomeController::class, 'laporanManager'])->name('manager.laporan');
        Route::get('/log', [HomeController::class, 'managerLog'])->name('manager.log');

        Route::get('/pegawai', [HomeController::class, 'managerPegawai'])->name('manager.pegawai');
        Route::post('/pegawai', [HomeController::class, 'managerTambahPegawai'])->name('manager.pegawai.tambah');
        Route::put('/pegawai/{id}', [HomeController::class, 'managerUpdatePegawai'])->name('manager.pegawai.update');
        Route::delete('/pegawai/{id}', [HomeController::class, 'managerHapusPegawai'])->name('manager.pegawai.hapus');

        Route::get('/pengumuman', [AnnouncementController::class, 'indexManager'])->name('manager.pengumuman.index');
        Route::post('/pengumuman', [AnnouncementController::class, 'storeManager'])->name('manager.pengumuman.store');
        Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroyManager'])->name('manager.pengumuman.delete');
    });


    // ======================================================================
    // ADMIN ROUTES
    // ======================================================================
    Route::prefix('admin')->group(function () {
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


    // ======================================================================
    // USER RAPOT & PENGUMUMAN
    // ======================================================================
    Route::get('/rapot', [RapotController::class, 'indexUser'])->name('rapot.user');
    Route::get('/pengumuman', [AnnouncementController::class, 'showToUsers'])->name('pengumuman.user');


    // ======================================================================
    // MANAGER PENGUMUMAN (ROLE VALIDATION)
    // ======================================================================
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/manager/pengumuman', [AnnouncementController::class, 'indexManager'])->name('manager.pengumuman.index');
        Route::post('/manager/pengumuman', [AnnouncementController::class, 'storeManager'])->name('manager.pengumuman.store');
        Route::delete('/manager/pengumuman/{id}', [AnnouncementController::class, 'destroyManager'])->name('manager.pengumuman.delete');
    });


    // ======================================================================
    // RAPOT ADVANCED ROUTES
    // ======================================================================
    Route::get('/my-rapot', [RapotController::class, 'indexUser'])->name('rapot.user');

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/rapot', [RapotController::class, 'indexAdmin'])->name('rapot.index');
        Route::post('/rapot/generate', [RapotController::class, 'generate'])->name('rapot.generate');
        Route::post('/rapot/generate-batch', [RapotController::class, 'generateBatch'])->name('rapot.generate.batch');

        Route::get('/rapot/{rapot}', [RapotController::class, 'show'])->name('rapot.show');
        Route::get('/rapot/{rapot}/edit', [RapotController::class, 'edit'])->name('rapot.edit');

        Route::put('/rapot/{rapot}', [RapotController::class, 'update'])->name('rapot.update');
        Route::delete('/rapot/{rapot}', [RapotController::class, 'destroy'])->name('rapot.delete');

        Route::get('/rapot/{rapot}/export-pdf', [RapotController::class, 'exportPDF'])->name('rapot.export.pdf');
        Route::get('/rapot/{rapot}/export-excel', [RapotController::class, 'exportExcel'])->name('rapot.export.excel');
        Route::get('/rapot/export-all', [RapotController::class, 'exportAllExcel'])->name('rapot.export.all');

        Route::post('/rapot/{rapot}/regenerate', [RapotController::class, 'regenerate'])->name('rapot.regenerate');
    });
    
    // Routes untuk generate rapor
Route::prefix('admin')->group(function () {
    Route::get('/rapot', [RapotController::class, 'index'])->name('admin.rapot.index');
    Route::post('/rapot/generate/{user}', [RapotController::class, 'generate'])->name('admin.rapot.generate');
    Route::get('/rapot/download/{filename}', [RapotController::class, 'download'])->name('admin.rapot.download');
    Route::get('/rapot/view/{filename}', [RapotController::class, 'view'])->name('admin.rapot.view');
    Route::get('/admin/rapot', [RapotController::class, 'index'])->name('rapot.index');

});

});
