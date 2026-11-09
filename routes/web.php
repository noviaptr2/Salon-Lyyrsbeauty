<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AntreanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar-akun', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar-akun', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profil/foto', [ProfileController::class, 'photo'])->name('profile.photo');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/antrean/daftar', [AntreanController::class, 'create'])->name('antrean.create');
    Route::get('/cek-antrean', [AntreanController::class, 'index'])->name('antrean.index');
    Route::post('/antrean/daftar', [AntreanController::class, 'store'])->name('antrean.store');

    Route::get('/antrean/{antrean}/pembayaran', [AntreanController::class, 'payment'])->name('antrean.payment');
    Route::post('/antrean/{antrean}/pembayaran/bukti', [AntreanController::class, 'uploadPaymentProof'])->name('antrean.payment.proof');

    Route::get('/antrean/{antrean}', [AntreanController::class, 'show'])->name('antrean.show');
    Route::get('/antrean/{antrean}/edit', [AntreanController::class, 'edit'])->name('antrean.edit');
    Route::put('/antrean/{antrean}', [AntreanController::class, 'update'])->name('antrean.update');
    Route::post('/antrean/{antrean}/batal', [AntreanController::class, 'cancel'])->name('antrean.cancel');
    Route::delete('/antrean/{antrean}', [AntreanController::class, 'destroy'])->name('antrean.destroy');

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::patch('/admin/antrean/{antrean}/status', [AdminController::class, 'updateStatus'])->name('admin.antrean.status');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::post('/antrean/{antrean}/pembayaran/verifikasi', [AdminController::class, 'verifyPayment'])->name('antrean.payment.verify');
        Route::post('/antrean/{antrean}/pembayaran/tolak', [AdminController::class, 'rejectPayment'])->name('antrean.payment.reject');

        Route::get('/layanan', [LayananController::class, 'adminIndex'])->name('layanan.index');
        Route::get('/layanan/tambah', [LayananController::class, 'create'])->name('layanan.create');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::get('/layanan/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
        Route::put('/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    });
});
