<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
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
        Route::get('/booking/{book}/payment', [CustomerController::class, 'paymentPage'])
            ->name('customer.booking.payment');
        Route::post('/booking/{book}/payment', [CustomerController::class, 'processMockPayment'])
            ->name('customer.booking.payment.process');
        Route::get('/booking/confirmation/{book}', [CustomerController::class, 'bookingConfirmation'])
            ->name('customer.booking.confirmation');
        Route::get('/my-bookings', [CustomerController::class, 'history'])->name('customer.history');
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

    Route::get('/bookings', [BookingController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])
        ->middleware('role:admin,staff')
        ->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])
        ->middleware('role:admin,staff')
        ->name('bookings.store');
    Route::get('/bookings/{book}', [BookingController::class, 'show'])
        ->middleware('role:admin,staff')
        ->name('bookings.show');
    Route::get('/bookings/{book}/edit', [BookingController::class, 'edit'])
        ->middleware('role:admin,staff')
        ->name('bookings.edit');
    Route::patch('/bookings/{book}/status', [BookingController::class, 'updateStatus'])
        ->middleware('role:admin,staff')
        ->name('bookings.status');
    Route::patch('/bookings/{book}/cancel', [BookingController::class, 'cancel'])
        ->middleware('role:admin,staff')
        ->name('bookings.cancel');
    Route::match(['put', 'patch'], '/bookings/{book}', [BookingController::class, 'update'])
        ->middleware('role:admin,staff')
        ->name('bookings.update');
    Route::delete('/bookings/{book}', [BookingController::class, 'destroy'])
        ->middleware('role:admin,staff')
        ->name('bookings.destroy');

    Route::get('/summary.php', [BookingController::class, 'summary'])
        ->middleware('role:admin,staff')
        ->name('summary.page');

    Route::get('/book.php', function () {
        return redirect()->route('bookings.create', request()->query());
    })->middleware('role:admin,staff')->name('book.page');

    Route::get('/reserve.php', function () {
        return redirect()->route('bookings.create', request()->query());
    })->middleware('role:admin,staff')->name('reserve.page');

    // Staff Routes
    Route::middleware('role:staff,admin')->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
        Route::get('/staff/bookings', [StaffController::class, 'bookings'])->name('staff.bookings');
        Route::get('/staff/bookings/{book}', [StaffController::class, 'showBooking'])->name('staff.booking-detail');
        Route::post('/staff/bookings/{book}/checkin', [StaffController::class, 'checkIn'])->name('staff.checkin');
        Route::post('/staff/bookings/{book}/checkout', [StaffController::class, 'checkOut'])->name('staff.checkout');
        Route::get('/staff/rooms', [StaffController::class, 'rooms'])->name('staff.rooms');
        Route::post('/staff/rooms/{room}/mark-clean', [StaffController::class, 'markRoomClean'])->name('staff.rooms.mark-clean');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout.php', [AuthController::class, 'logout'])->name('logout.page');
});
