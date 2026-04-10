<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            if (! $user) {
                return redirect()->route('login')->with('warning', 'Please sign in to continue.');
            }

            return redirect()->route($this->homeRouteForRole($user->role))
                ->with('warning', 'Your account does not have access to that section. We redirected you to the right area.');
        }

        return $next($request);
    }

    private function homeRouteForRole(?string $role): string
    {
        return match ($role) {
            'admin' => 'dashboard',
            'staff' => 'staff.dashboard',
            default => 'customer.home',
        };
    }
}
