<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('/login.php', [AuthController::class, 'login'])->name('login.page.attempt');

    Route::get('/login.php', function () {
        return redirect()->route('login');
    })->name('login.page');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard.php', function () {
        return redirect()->route('dashboard');
    })->name('dashboard.page');

    Route::get('/rooms/search', [RoomSearchController::class, 'index'])->name('rooms.search');
    Route::get('/search.php', function () {
        return redirect()->route('rooms.search', request()->query());
    })->name('search.page');

    Route::get('/reservations', [ReservationController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])
        ->middleware('role:admin,staff')
        ->name('reservations.edit');
    Route::match(['put', 'patch'], '/reservations/{reservation}', [ReservationController::class, 'update'])
        ->middleware('role:admin,staff')
        ->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->middleware('role:admin,staff')
        ->name('reservations.destroy');

    Route::get('/summary.php', [ReservationController::class, 'summary'])->name('summary.page');

    Route::get('/reserve.php', function () {
        return redirect()->route('reservations.create', request()->query());
    })->name('reserve.page');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout.php', [AuthController::class, 'logout'])->name('logout.page');
});
