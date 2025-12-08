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

// PUBLIC
Route::get('/', fn() => view('welcome'));
Route::get('/home', fn() => view('home'))->name('home');
Route::get('/login-pegawai', fn() => view('login-pegawai'))->name('login.pegawai');

// ======================================================================
// AUTH ROUTES (GUEST)
// ======================================================================
Route::middleware('guest')->group(function () {
    // Route login menggunakan no_hp
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Jika masih ingin menggunakan register, sesuaikan dengan no_hp
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Jika ingin tetap ada fitur forgot password, sesuaikan dengan no_hp
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// ======================================================================
// AUTH ROUTES (AUTHENTICATED)
// ======================================================================
Route::middleware('auth')->group(function () {
    // Hapus route email verification karena menggunakan no_hp bukan email
    // Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // DASHBOARD REDIRECT OTOMATIS BERDASARKAN ROLE
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ======================================================================
    // ROUTES YANG HANYA BOLEH DIAKSES JIKA LOGIN
    // ======================================================================

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
            
        // Pengumuman Manager (sudah ada di bawah, tapi ini untuk konsistensi)
        Route::get('/pengumuman', [AnnouncementController::class, 'indexManager'])->name('manager.pengumuman.index');
        Route::post('/pengumuman', [AnnouncementController::class, 'storeManager'])->name('manager.pengumuman.store');
        Route::delete('/pengumuman/{id}', [AnnouncementController::class, 'destroyManager'])->name('manager.pengumuman.delete');
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
        Route::post('/rapot/generate/pdf/{id}', [RapotController::class, 'generatePDF'])->name('admin.rapot.generate.pdf');
        Route::post('/rapot/generate/excel/{id}', [RapotController::class, 'generateExcel'])->name('admin.rapot.generate.excel');

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
    Route::get('/pengumuman', [AnnouncementController::class, 'showToUsers'])->name('pengumuman.user');

    // ======================================================================
    // PENGUMUMAN MANAGER (alternatif grouping)
    // ======================================================================
    Route::middleware(['auth', 'role:manager'])->group(function() {
        Route::get('/manager/pengumuman', [AnnouncementController::class, 'indexManager'])->name('manager.pengumuman.index');
        Route::post('/manager/pengumuman', [AnnouncementController::class, 'storeManager'])->name('manager.pengumuman.store');
        Route::delete('/manager/pengumuman/{id}', [AnnouncementController::class, 'destroyManager'])->name('manager.pengumuman.delete');
    });
});
