<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'StayEase Hotel' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg: #faf8f4;
            --surface: #ffffff;
            --nav-bg: #ffffff;
            --nav-hover: #f5f3ef;
            --gold: #b99a61;
            --gold-deep: #9e814d;
            --border: #e1dacb;
            --text: #2c2c2c;
            --muted: #9ca3af;
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background: var(--bg);
        }

        h1,
        h2,
        h3,
        .brand-font,
        .section-title {
            font-family: 'Cormorant Garamond', serif;
        }

        .surface {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 12px 26px rgba(30, 32, 34, 0.06);
        }

        .section-title {
            letter-spacing: 0.01em;
        }

        .metric-value {
            font-family: 'JetBrains Mono', monospace;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold), var(--gold-deep));
            color: #fff;
            border-radius: 12px;
            font-weight: 700;
            transition: transform 0.16s ease, box-shadow 0.16s ease, opacity 0.16s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(185, 154, 97, 0.28);
        }

        .btn-secondary {
            background: #ede8dd;
            color: #2b3442;
            border-radius: 12px;
            font-weight: 700;
        }

        .nav-link {
            border-radius: 12px;
            padding: 0.56rem 0.85rem;
            transition: background 0.16s ease, color 0.16s ease;
            font-size: 0.92rem;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
        }

        .nav-link:not(.active) {
            color: #6b7280;
        }

        .nav-link:not(.active):hover {
            background: var(--nav-hover);
            color: #2c2c2c;
        }

        .luxury-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(185, 154, 97, 0.5), transparent);
        }

        .top-nav-wrap {
            background: var(--nav-bg);
            border-bottom: 1px solid rgba(185, 154, 97, 0.12);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .profile-menu {
            position: relative;
        }

        .menu-cluster {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notify-menu {
            position: relative;
        }

        .notify-trigger {
            list-style: none;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 9999px;
            border: 1px solid rgba(120, 127, 140, 0.28);
            background: #ffffff;
            color: #1f2937;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(22, 28, 36, 0.08);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
            user-select: none;
        }

        .notify-trigger:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(22, 28, 36, 0.12);
        }

        .notify-trigger::-webkit-details-marker {
            display: none;
        }

        .notify-icon {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .notify-count {
            position: absolute;
            right: -2px;
            top: -3px;
            min-width: 18px;
            height: 18px;
            border-radius: 9999px;
            background: #b91c1c;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            padding: 0 4px;
        }

        .notify-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 0.6rem);
            width: 290px;
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.22);
            background: #fff;
            box-shadow: 0 16px 28px rgba(25, 20, 13, 0.16);
            padding: 0.75rem;
            z-index: 60;
        }

        .notify-title {
            color: #2c2c2c;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .notify-list {
            margin-top: 0.55rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .notify-item {
            border-radius: 10px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            padding: 0.52rem 0.6rem;
            font-size: 0.8rem;
            line-height: 1.35;
            color: #374151;
            background: #fcfbf8;
            display: block;
        }

        .notify-item:hover {
            background: #f7f2e8;
        }

        .notify-item.warn {
            border-color: rgba(245, 158, 11, 0.35);
            background: #fff9ec;
        }

        .notify-item.info {
            border-color: rgba(59, 130, 246, 0.28);
            background: #f5f9ff;
        }

        .notify-item.success {
            border-color: rgba(16, 185, 129, 0.3);
            background: #f3fff8;
        }

        .profile-trigger {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 9999px;
            border: 1px solid rgba(120, 127, 140, 0.28);
            background: #ffffff;
            color: #1f2937;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(22, 28, 36, 0.08);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
            user-select: none;
        }

        .profile-trigger:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(22, 28, 36, 0.12);
        }

        .profile-icon {
            width: 23px;
            height: 23px;
            stroke: currentColor;
            stroke-width: 2.2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .profile-trigger::-webkit-details-marker {
            display: none;
        }

        .profile-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 0.6rem);
            width: 220px;
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.22);
            background: #fff;
            box-shadow: 0 16px 28px rgba(25, 20, 13, 0.16);
            padding: 0.75rem;
            z-index: 60;
        }

        .profile-name {
            color: #2c2c2c;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .profile-email {
            color: #7b7f88;
            font-size: 0.78rem;
            margin-top: 0.1rem;
            word-break: break-word;
        }

        .profile-divider {
            height: 1px;
            margin: 0.6rem 0;
            background: linear-gradient(to right, transparent, rgba(185, 154, 97, 0.45), transparent);
        }

        .logout-item {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(185, 154, 97, 0.24);
            background: #faf6ee;
            color: #7a1f1f;
            font-size: 0.86rem;
            font-weight: 700;
            padding: 0.55rem 0.7rem;
            text-align: left;
            transition: background 0.16s ease;
        }

        .logout-item:hover {
            background: #f9e5e5;
        }

        .brand-logo {
            height: 58px;
            width: 58px;
            border-radius: 9999px;
            object-fit: cover;
            object-position: center;
            background: #fff;
            border: 2px solid rgba(185, 154, 97, 0.45);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .brand-title {
            line-height: 1;
            font-size: 2rem;
        }

        @media (max-width: 640px) {
            .brand-logo {
                width: 50px;
                height: 50px;
            }

            .brand-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body class="min-h-screen">
    <div class="min-h-screen">
        <header class="top-nav-wrap sticky top-0 z-30 text-white">
            <div class="mx-auto flex w-full max-w-7xl flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:gap-4 sm:px-6">
                @php
                    $homeRoute = route('customer.home');

                    if (auth()->check() && auth()->user()->hasRole('admin')) {
                        $homeRoute = route('dashboard');
                    } elseif (auth()->check() && auth()->user()->hasRole('staff')) {
                        $homeRoute = route('staff.dashboard');
                    }
                @endphp

                <a href="{{ $homeRoute }}" class="flex shrink-0 items-center gap-3 text-gray-900">
                    <img src="{{ asset('images/aurelia_logo.png') }}" alt="StayEase Hotel logo" class="brand-logo" />
                    <span class="brand-title brand-font font-semibold tracking-wide">StayEase Hotel</span>
                </a>

                @auth
                    @php
                        $user = auth()->user();
                        $isAdmin = $user->hasRole('admin');
                        $isStaff = $user->hasRole('staff');
                        $isCustomer = $user->hasRole('customer');
                    @endphp

                    <nav class="flex flex-wrap items-center gap-2 text-sm sm:ml-2 lg:flex-nowrap">
                        @if ($isStaff && !$isAdmin)
                            <!-- Staff Navigation -->
                            <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">Dashboard</a>
                            <a href="{{ route('staff.reservations') }}" class="nav-link {{ request()->routeIs('staff.reservations', 'staff.reservation-detail') ? 'active' : '' }}">Reservations</a>
                            <a href="{{ route('staff.rooms') }}" class="nav-link {{ request()->routeIs('staff.rooms') ? 'active' : '' }}">Rooms</a>
                        @elseif ($isAdmin)
                            <!-- Admin Navigation -->
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                            <a href="{{ route('rooms.search') }}" class="nav-link {{ request()->routeIs('rooms.search') ? 'active' : '' }}">Search Rooms</a>
                            <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.index') ? 'active' : '' }}">Manage Reservations</a>
                            <a href="{{ route('reservations.create') }}" class="nav-link {{ request()->routeIs('reservations.create') ? 'active' : '' }}">New Reservation</a>
                        @else
                            <!-- Customer Navigation -->
                            <a href="{{ route('customer.home') }}" class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}">Home</a>
                            <a href="{{ route('customer.search') }}" class="nav-link {{ request()->routeIs('customer.search', 'customer.room.details') ? 'active' : '' }}">Search Rooms</a>
                            <a href="{{ route('customer.history') }}" class="nav-link {{ request()->routeIs('customer.history', 'customer.booking.confirmation') ? 'active' : '' }}">My Bookings</a>
                        @endif
                    </nav>

                    <div class="menu-cluster">
                        <details class="notify-menu">
                            <summary class="notify-trigger">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="notify-icon">
                                    <path d="M15 17H5.5c-.7 0-1.1-.8-.7-1.4l1.5-2.2V9.7C6.3 6.6 8.8 4 12 4s5.7 2.6 5.7 5.7v3.7l1.5 2.2c.4.6 0 1.4-.7 1.4H18"></path>
                                    <path d="M9.5 18.5c.4 1.2 1.4 1.9 2.5 1.9s2.1-.7 2.5-1.9"></path>
                                </svg>
                                @if (($navNotifications['count'] ?? 0) > 0)
                                    <span class="notify-count">{{ $navNotifications['count'] > 9 ? '9+' : $navNotifications['count'] }}</span>
                                @endif
                            </summary>
                            <div class="notify-dropdown">
                                <p class="notify-title">Notifications</p>
                                <div class="notify-list">
                                    @foreach (($navNotifications['items'] ?? []) as $note)
                                        @if ($note['href'])
                                            <a href="{{ $note['href'] }}" class="notify-item {{ $note['tone'] }}">{{ $note['label'] }}</a>
                                        @else
                                            <p class="notify-item {{ $note['tone'] }}">{{ $note['label'] }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </details>

                        <details class="profile-menu">
                            <summary class="profile-trigger">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="profile-icon">
                                    <circle cx="12" cy="8" r="4.3"></circle>
                                    <path d="M4.5 19.5c1.8-3.5 4.2-5.2 7.5-5.2s5.7 1.7 7.5 5.2"></path>
                                </svg>
                            </summary>
                            <div class="profile-dropdown">
                                <p class="profile-name">{{ $user->name }}</p>
                                <p class="profile-email">{{ $user->email }}</p>
                                <div class="profile-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="logout-item">Sign out</button>
                                </form>
                            </div>
                        </details>
                    </div>
                @endauth

                @guest
                    <nav class="flex flex-wrap items-center gap-2 text-sm sm:ml-2 lg:flex-nowrap">
                        <a href="{{ route('customer.home') }}" class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('customer.search') }}" class="nav-link {{ request()->routeIs('customer.search', 'customer.room.details') ? 'active' : '' }}">Search Rooms</a>
                        <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                        <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">Register</a>
                    </nav>
                @endguest
            </div>
            <div class="luxury-divider mx-4 sm:mx-6"></div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8">
                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="mt-1 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popouts = Array.from(document.querySelectorAll('.notify-menu, .profile-menu'));

            if (popouts.length === 0) {
                return;
            }

            popouts.forEach((menu) => {
                menu.addEventListener('toggle', () => {
                    if (!menu.open) {
                        return;
                    }

                    popouts.forEach((other) => {
                        if (other !== menu) {
                            other.removeAttribute('open');
                        }
                    });
                });
            });

            document.addEventListener('click', (event) => {
                const insideAnyPopout = event.target.closest('.notify-menu, .profile-menu');
                if (!insideAnyPopout) {
                    popouts.forEach((menu) => menu.removeAttribute('open'));
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    popouts.forEach((menu) => menu.removeAttribute('open'));
                }
            });
        });
    </script>
</body>

</html>
