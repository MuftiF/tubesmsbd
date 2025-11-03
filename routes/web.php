<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login-pegawai', function () {
    return view('login-pegawai');
})->name('login.pegawai');

Route::get('/home', function () {
    return view('home'); // Buat file ini nanti
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

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin']);

Route::get('security/dashboard', function () {
    return view('security.dashboard');
})->middleware(['auth', 'security']);

Route::get('manager/dashboard', function () {
    return view('manager.dashboard');
})->middleware(['auth', 'manager']);

Route::get('cleaning/dashboard', function () {
    return view('cleaning.dashboard');
})->middleware(['auth', 'cleaning']);

Route::get('kantoran/dashboard', function () {
    return view('kantoran.dashboard');
})->middleware(['auth', 'kantoran']);