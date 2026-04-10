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
            background:
                radial-gradient(circle at top, rgba(185, 154, 97, 0.08), transparent 28%),
                linear-gradient(180deg, #fbf9f5 0%, var(--bg) 24%, #f7f3ee 100%);
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 6.5rem;
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

        .nav-link.nav-action {
            padding: 0.64rem 1rem;
            border: 1px solid transparent;
        }

        .nav-link.nav-action.secondary {
            background: #fbf8f1;
            border-color: rgba(185, 154, 97, 0.18);
            color: #4f4027;
        }

        .nav-link.nav-action.secondary:hover {
            background: #f3ece0;
            color: #342712;
        }

        .nav-link.nav-action.primary {
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
            box-shadow: 0 8px 16px rgba(185, 154, 97, 0.18);
        }

        .nav-link.nav-action.primary:hover {
            background: linear-gradient(135deg, #c7ac75, #aa8b54);
            color: #fff;
            box-shadow: 0 10px 18px rgba(185, 154, 97, 0.25);
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
            background: rgba(255, 255, 255, 0.88);
            border-bottom: 1px solid rgba(185, 154, 97, 0.12);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
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

        .nav-shell {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .nav-links,
        .nav-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-actions {
            width: 100%;
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

        .site-footer {
            margin-top: 1.5rem;
            border-top: 1px solid rgba(185, 154, 97, 0.14);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.7), rgba(248, 243, 234, 0.95));
        }

        .footer-link {
            color: #6b7280;
            transition: color 0.16s ease;
        }

        .footer-link:hover {
            color: #2c2c2c;
        }

        .footer-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 9999px;
            border: 1px solid rgba(185, 154, 97, 0.18);
            background: rgba(255, 255, 255, 0.7);
            padding: 0.45rem 0.75rem;
            color: #4f4027;
            font-size: 0.84rem;
            font-weight: 600;
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

            .nav-actions {
                justify-content: flex-start;
            }
        }

        @media (min-width: 640px) {
            .nav-shell {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
            }

            .nav-actions {
                width: auto;
                margin-left: auto;
                justify-content: flex-end;
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

                <div class="nav-shell">
                    @auth
                        @php
                            $user = auth()->user();
                            $isAdmin = $user->hasRole('admin');
                            $isStaff = $user->hasRole('staff');
                            $isCustomer = $user->hasRole('customer');
                        @endphp

                        <nav class="nav-links text-sm">
                            @if ($isStaff && !$isAdmin)
                                <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">Dashboard</a>
                                <a href="{{ route('staff.reservations') }}" class="nav-link {{ request()->routeIs('staff.reservations', 'staff.reservation-detail') ? 'active' : '' }}">Reservations</a>
                                <a href="{{ route('staff.rooms') }}" class="nav-link {{ request()->routeIs('staff.rooms') ? 'active' : '' }}">Rooms</a>
                            @elseif ($isAdmin)
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                                <a href="{{ route('rooms.search') }}" class="nav-link {{ request()->routeIs('rooms.search') ? 'active' : '' }}">Search Rooms</a>
                                <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.index') ? 'active' : '' }}">Manage Reservations</a>
                                <a href="{{ route('reservations.create') }}" class="nav-link {{ request()->routeIs('reservations.create') ? 'active' : '' }}">New Reservation</a>
                            @else
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
                        <nav class="nav-links text-sm">
                            <a href="{{ route('customer.home') }}" class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}">Home</a>
                            <a href="{{ route('customer.search') }}" class="nav-link {{ request()->routeIs('customer.search', 'customer.room.details') ? 'active' : '' }}">Browse Rooms</a>
                            <a href="{{ route('customer.home') }}#featured-rooms" class="nav-link">Featured Rooms</a>
                            <a href="{{ route('customer.home') }}#how-it-works" class="nav-link">How It Works</a>
                            <a href="{{ route('customer.home') }}#about-us" class="nav-link">About Us</a>
                        </nav>

                        <div class="nav-actions">
                            <a href="{{ route('login') }}" class="nav-link nav-action secondary">Sign In</a>
                            <a href="{{ route('register') }}" class="nav-link nav-action primary">Join Us</a>
                        </div>
                    @endguest
                </div>
            </div>
            <div class="luxury-divider mx-4 sm:mx-6"></div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8">
                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-5 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-amber-800">
                        {{ session('warning') }}
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

        <footer class="site-footer">
            <div class="mx-auto grid w-full max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:grid-cols-[1.3fr_0.9fr_0.9fr] lg:py-10">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/aurelia_logo.png') }}" alt="StayEase Hotel logo" class="brand-logo" />
                        <div>
                            <p class="brand-font text-3xl font-semibold leading-none text-stone-900">StayEase Hotel</p>
                            <p class="mt-1 text-sm text-stone-600">Clean booking flow, premium stays, and responsive service.</p>
                        </div>
                    </div>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-stone-600">
                        A polished hospitality experience designed to help guests discover rooms quickly, book confidently,
                        and return with ease.
                    </p>
                </div>

                <div>
                    <h2 class="section-title text-2xl font-bold text-stone-900">Quick Links</h2>
                    <div class="mt-4 flex flex-col gap-2 text-sm">
                        <a href="{{ route('customer.home') }}" class="footer-link">Home</a>
                        <a href="{{ route('customer.search') }}" class="footer-link">Browse Rooms</a>
                        <a href="{{ route('customer.home') }}#featured-rooms" class="footer-link">Featured Rooms</a>
                        <a href="{{ route('customer.home') }}#about-us" class="footer-link">About Us</a>
                    </div>
                </div>

                <div>
                    <h2 class="section-title text-2xl font-bold text-stone-900">Stay Connected</h2>
                    <div class="mt-4 flex flex-col gap-2 text-sm">
                        <span class="footer-chip">Open daily for bookings</span>
                        <span class="footer-chip">Support available 24/7</span>
                        <span class="footer-chip">Fast room discovery</span>
                    </div>
                    <p class="mt-4 text-sm text-stone-600">Login for reservations, or register to manage your stays in one place.</p>
                </div>
            </div>

            <div class="luxury-divider mx-4 sm:mx-6"></div>

            <div class="mx-auto flex w-full max-w-7xl flex-col gap-3 px-4 py-4 text-sm text-stone-500 sm:px-6 md:flex-row md:items-center md:justify-between">
                <p>© {{ now()->year }} StayEase Hotel. All rights reserved.</p>
                <p>Refined hospitality, from search to stay.</p>
            </div>
        </footer>
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
