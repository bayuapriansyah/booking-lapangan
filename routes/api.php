<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;



Route::get('/reservations', [ReservationController::class, 'getReservations']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Additional API endpoints if needed
    Route::get('/courts', function () {
        return \App\Models\Court::where('is_active', true)->get();
    });

    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);




    Route::get('/my-reservations', function (Request $request) {
        return $request->user()
            ->reservations()
            ->with('court')
            ->orderBy('start_time', 'desc')
            ->get();
    });
});