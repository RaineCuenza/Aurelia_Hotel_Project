<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomSearchController;
use App\Http\Controllers\StaffController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customer.home');
});

Route::get('/home', [CustomerController::class, 'home'])->name('customer.home');
Route::get('/rooms', [CustomerController::class, 'search'])->name('customer.search');
Route::get('/rooms/{room}', [CustomerController::class, 'roomDetails'])
    ->whereNumber('room')
    ->name('customer.room.details');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
    Route::post('/login.php', [AuthController::class, 'login'])->name('login.page.attempt');

    Route::get('/login.php', function () {
        return redirect()->route('login');
    })->name('login.page');
});

Route::get('/email/verify/{id}/{hash}', function (Request $request, int $id, string $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    if (! Auth::check()) {
        Auth::login($user);
        $request->session()->regenerate();
    }

    return redirect()->route('customer.home')->with('success', 'Email verified successfully.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'A new verification link has been sent to your email address.');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::middleware(['role:customer', 'verified'])->group(function () {
        Route::get('/book/{room}', [CustomerController::class, 'bookingForm'])
            ->whereNumber('room')
            ->name('customer.booking.form');
        Route::post('/book/{room}', [CustomerController::class, 'book'])
            ->whereNumber('room')
            ->name('customer.booking.store');
        Route::get('/booking/confirmation/{reservation}', [CustomerController::class, 'bookingConfirmation'])
            ->name('customer.booking.confirmation');
        Route::get('/my-reservations', [CustomerController::class, 'history'])->name('customer.history');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard');
    Route::get('/dashboard.php', function () {
        return redirect()->route('dashboard');
    })->name('dashboard.page');

    Route::get('/rooms/search', [RoomSearchController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('rooms.search');
    Route::get('/search.php', function () {
        return redirect()->route('rooms.search', request()->query());
    })->name('search.page');

    Route::get('/reservations', [ReservationController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])
        ->middleware('role:admin,staff')
        ->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])
        ->middleware('role:admin,staff')
        ->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])
        ->middleware('role:admin,staff')
        ->name('reservations.show');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])
        ->middleware('role:admin,staff')
        ->name('reservations.edit');
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])
        ->middleware('role:admin,staff')
        ->name('reservations.status');
    Route::match(['put', 'patch'], '/reservations/{reservation}', [ReservationController::class, 'update'])
        ->middleware('role:admin,staff')
        ->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->middleware('role:admin,staff')
        ->name('reservations.destroy');

    Route::get('/summary.php', [ReservationController::class, 'summary'])
        ->middleware('role:admin,staff')
        ->name('summary.page');

    Route::get('/reserve.php', function () {
        return redirect()->route('reservations.create', request()->query());
    })->middleware('role:admin,staff')->name('reserve.page');

    // Staff Routes
    Route::middleware('role:staff,admin')->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
        Route::get('/staff/reservations', [StaffController::class, 'reservations'])->name('staff.reservations');
        Route::get('/staff/reservations/{reservation}', [StaffController::class, 'showReservation'])->name('staff.reservation-detail');
        Route::post('/staff/reservations/{reservation}/checkin', [StaffController::class, 'checkIn'])->name('staff.checkin');
        Route::post('/staff/reservations/{reservation}/checkout', [StaffController::class, 'checkOut'])->name('staff.checkout');
        Route::get('/staff/rooms', [StaffController::class, 'rooms'])->name('staff.rooms');
        Route::post('/staff/rooms/{room}/mark-clean', [StaffController::class, 'markRoomClean'])->name('staff.rooms.mark-clean');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout.php', [AuthController::class, 'logout'])->name('logout.page');
});
