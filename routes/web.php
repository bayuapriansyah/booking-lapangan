<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/api/reservations', [ReservationController::class, 'getReservations'])->name('api.reservations');

    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/{reservation}', [PaymentController::class, 'show'])->name('payment.show');
        Route::post('/{reservation}', [PaymentController::class, 'process'])->name('payment.process');
    });
});

require __DIR__ . '/auth.php';
