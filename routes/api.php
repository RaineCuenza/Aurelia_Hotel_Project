<?php

use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\RoomApiController;
use Illuminate\Support\Facades\Route;

Route::get('/rooms', [RoomApiController::class, 'index'])->name('api.rooms.index');
Route::get('/rooms/{room}', [RoomApiController::class, 'show'])->name('api.rooms.show');

Route::get('/bookings', [BookingApiController::class, 'index'])->name('api.bookings.index');
Route::post('/bookings', [BookingApiController::class, 'store'])->name('api.bookings.store');
Route::get('/bookings/{book}', [BookingApiController::class, 'show'])->name('api.bookings.show');
Route::match(['put', 'patch'], '/bookings/{book}', [BookingApiController::class, 'update'])->name('api.bookings.update');
Route::delete('/bookings/{book}', [BookingApiController::class, 'destroy'])->name('api.bookings.destroy');
