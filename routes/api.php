<?php

use App\Http\Controllers\Api\ReservationApiController;
use App\Http\Controllers\Api\RoomApiController;
use Illuminate\Support\Facades\Route;

Route::get('/rooms', [RoomApiController::class, 'index'])->name('api.rooms.index');
Route::get('/rooms/{room}', [RoomApiController::class, 'show'])->name('api.rooms.show');

Route::get('/reservations', [ReservationApiController::class, 'index'])->name('api.reservations.index');
Route::post('/reservations', [ReservationApiController::class, 'store'])->name('api.reservations.store');
Route::get('/reservations/{reservation}', [ReservationApiController::class, 'show'])->name('api.reservations.show');
Route::match(['put', 'patch'], '/reservations/{reservation}', [ReservationApiController::class, 'update'])->name('api.reservations.update');
Route::delete('/reservations/{reservation}', [ReservationApiController::class, 'destroy'])->name('api.reservations.destroy');
