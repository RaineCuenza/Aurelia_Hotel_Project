<?php

namespace App\Providers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            $payload = [
                'count' => 0,
                'items' => [],
            ];

            if (! Auth::check()) {
                $view->with('navNotifications', $payload);
                return;
            }

            $user = Auth::user();
            $today = now()->toDateString();
            $items = [];

            if ($user->role === 'admin') {
                $pending = Reservation::where('status', 'pending')->count();
                $arrivalsToday = Reservation::whereDate('check_in', $today)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->count();
                $checkoutsToday = Reservation::whereDate('check_out', $today)
                    ->where('status', 'checked_in')
                    ->count();
                $roomsCleaning = Room::where('status', 'cleaning')->count();

                if ($pending > 0) {
                    $items[] = [
                        'label' => $pending.' booking(s) pending approval',
                        'href' => route('reservations.index'),
                        'tone' => 'warn',
                    ];
                }

                if ($arrivalsToday > 0) {
                    $items[] = [
                        'label' => $arrivalsToday.' arrival(s) scheduled today',
                        'href' => route('reservations.index'),
                        'tone' => 'info',
                    ];
                }

                if ($checkoutsToday > 0) {
                    $items[] = [
                        'label' => $checkoutsToday.' check-out(s) due today',
                        'href' => route('reservations.index'),
                        'tone' => 'info',
                    ];
                }

                if ($roomsCleaning > 0) {
                    $items[] = [
                        'label' => $roomsCleaning.' room(s) under cleaning',
                        'href' => route('staff.rooms'),
                        'tone' => 'neutral',
                    ];
                }
            } elseif ($user->role === 'staff') {
                $checkinsToday = Reservation::whereDate('check_in', $today)
                    ->where('status', 'confirmed')
                    ->count();
                $checkoutsToday = Reservation::whereDate('check_out', $today)
                    ->where('status', 'checked_in')
                    ->count();
                $roomsCleaning = Room::where('status', 'cleaning')->count();

                if ($checkinsToday > 0) {
                    $items[] = [
                        'label' => $checkinsToday.' guest(s) ready for check-in',
                        'href' => route('staff.reservations'),
                        'tone' => 'info',
                    ];
                }

                if ($checkoutsToday > 0) {
                    $items[] = [
                        'label' => $checkoutsToday.' guest(s) due for check-out',
                        'href' => route('staff.reservations'),
                        'tone' => 'warn',
                    ];
                }

                if ($roomsCleaning > 0) {
                    $items[] = [
                        'label' => $roomsCleaning.' room(s) waiting for readiness',
                        'href' => route('staff.rooms'),
                        'tone' => 'neutral',
                    ];
                }
            } elseif ($user->role === 'customer') {
                $upcoming = Reservation::where('user_id', $user->id)
                    ->whereDate('check_in', '>=', $today)
                    ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                    ->count();

                $confirmedRecent = Reservation::where('user_id', $user->id)
                    ->where('status', 'confirmed')
                    ->where('created_at', '>=', now()->subDay())
                    ->count();

                if ($upcoming > 0) {
                    $items[] = [
                        'label' => 'You have '.$upcoming.' upcoming booking(s)',
                        'href' => route('customer.history'),
                        'tone' => 'info',
                    ];
                }

                if ($confirmedRecent > 0) {
                    $items[] = [
                        'label' => $confirmedRecent.' booking(s) recently confirmed',
                        'href' => route('customer.history'),
                        'tone' => 'success',
                    ];
                }
            }

            if (count($items) === 0) {
                $items[] = [
                    'label' => 'No new notifications',
                    'href' => null,
                    'tone' => 'neutral',
                ];
            }

            $payload['count'] = collect($items)
                ->filter(fn (array $item): bool => $item['label'] !== 'No new notifications')
                ->count();
            $payload['items'] = $items;

            $view->with('navNotifications', $payload);
        });
    }
}
