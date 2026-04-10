<?php

namespace App\Providers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Carbon;
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
            $buildItem = static function (string $label, ?string $href, string $tone, Carbon|string|null $time = null): array {
                $at = $time instanceof Carbon
                    ? $time
                    : ($time ? Carbon::parse($time) : now());

                return [
                    'label' => $label,
                    'href' => $href,
                    'tone' => $tone,
                    'time_human' => $at->diffForHumans(),
                    'time_full' => $at->format('M d, Y h:i A'),
                ];
            };

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
                    $items[] = $buildItem(
                        $pending.' booking(s) pending approval',
                        route('reservations.index'),
                        'warn',
                        Reservation::where('status', 'pending')->latest('updated_at')->value('updated_at')
                    );
                }

                if ($arrivalsToday > 0) {
                    $items[] = $buildItem(
                        $arrivalsToday.' arrival(s) scheduled today',
                        route('reservations.index'),
                        'info',
                        Reservation::whereDate('check_in', $today)
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->latest('updated_at')
                            ->value('updated_at')
                    );
                }

                if ($checkoutsToday > 0) {
                    $items[] = $buildItem(
                        $checkoutsToday.' check-out(s) due today',
                        route('reservations.index'),
                        'info',
                        Reservation::whereDate('check_out', $today)
                            ->where('status', 'checked_in')
                            ->latest('updated_at')
                            ->value('updated_at')
                    );
                }

                if ($roomsCleaning > 0) {
                    $items[] = $buildItem(
                        $roomsCleaning.' room(s) under cleaning',
                        route('staff.rooms'),
                        'neutral',
                        Room::where('status', 'cleaning')->latest('updated_at')->value('updated_at')
                    );
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
                    $items[] = $buildItem(
                        $checkinsToday.' guest(s) ready for check-in',
                        route('staff.reservations'),
                        'info',
                        Reservation::whereDate('check_in', $today)
                            ->where('status', 'confirmed')
                            ->latest('updated_at')
                            ->value('updated_at')
                    );
                }

                if ($checkoutsToday > 0) {
                    $items[] = $buildItem(
                        $checkoutsToday.' guest(s) due for check-out',
                        route('staff.reservations'),
                        'warn',
                        Reservation::whereDate('check_out', $today)
                            ->where('status', 'checked_in')
                            ->latest('updated_at')
                            ->value('updated_at')
                    );
                }

                if ($roomsCleaning > 0) {
                    $items[] = $buildItem(
                        $roomsCleaning.' room(s) waiting for readiness',
                        route('staff.rooms'),
                        'neutral',
                        Room::where('status', 'cleaning')->latest('updated_at')->value('updated_at')
                    );
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
                    $items[] = $buildItem(
                        'You have '.$upcoming.' upcoming booking(s)',
                        route('customer.history'),
                        'info',
                        Reservation::where('user_id', $user->id)
                            ->whereDate('check_in', '>=', $today)
                            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                            ->latest('updated_at')
                            ->value('updated_at')
                    );
                }

                if ($confirmedRecent > 0) {
                    $items[] = $buildItem(
                        $confirmedRecent.' booking(s) recently confirmed',
                        route('customer.history'),
                        'success',
                        Reservation::where('user_id', $user->id)
                            ->where('status', 'confirmed')
                            ->where('created_at', '>=', now()->subDay())
                            ->latest('updated_at')
                            ->value('updated_at')
                    );
                }
            }

            if (count($items) === 0) {
                $items[] = $buildItem('No new notifications', null, 'neutral');
            }

            $payload['count'] = collect($items)
                ->filter(fn (array $item): bool => $item['label'] !== 'No new notifications')
                ->count();
            $payload['items'] = $items;

            $view->with('navNotifications', $payload);
        });
    }
}
