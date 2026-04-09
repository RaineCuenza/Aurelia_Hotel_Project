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
            --bg: #f4f2ed;
            --surface: #fcfbf8;
            --sidebar: #0d1623;
            --sidebar-hover: #1b2a3d;
            --gold: #b99a61;
            --gold-deep: #9e814d;
            --border: #e1dacb;
            --text: #1b2431;
            --muted: #5f6673;
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 10% 8%, #ebe7df 0%, transparent 32%),
                radial-gradient(circle at 88% 2%, #eee7da 0%, transparent 28%),
                var(--bg);
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
            padding: 0.68rem 0.9rem;
            transition: background 0.16s ease, color 0.16s ease;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(185, 154, 97, 0.92), rgba(158, 129, 77, 0.92));
            color: #fff;
        }

        .nav-link:not(.active) {
            color: #d8deea;
        }

        .nav-link:not(.active):hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .luxury-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(185, 154, 97, 0.5), transparent);
        }
    </style>
</head>

<body class="min-h-screen">
    <div class="min-h-screen md:grid md:grid-cols-[280px_1fr]">
        <aside class="bg-[var(--sidebar)] text-white md:sticky md:top-0 md:h-screen">
            <div class="px-5 py-6">
                <a href="{{ route('dashboard') }}" class="brand-font text-3xl font-semibold tracking-wide text-amber-50">StayEase Hotel</a>
                @auth
                    <p class="mt-3 text-xs text-slate-300">
                        <span class="font-semibold text-slate-100">{{ auth()->user()->name }}</span>
                        <span class="ml-1 rounded-full border border-[#3d4656] bg-[#1c2634] px-2 py-0.5 uppercase tracking-wide text-[#dbc79f]">{{ auth()->user()->role }}</span>
                    </p>
                @endauth
            </div>

            <div class="luxury-divider mx-4"></div>

            @auth
                @php
                    $user = auth()->user();
                    $isAdmin = $user->hasRole('admin');
                    $isStaff = $user->hasRole('staff');
                    $isCustomer = $user->hasRole('customer');
                @endphp

                <nav class="px-3 py-4 text-sm">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link mb-1 block {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('rooms.search') }}"
                        class="nav-link mb-1 block {{ request()->routeIs('rooms.search') ? 'active' : '' }}">
                        Search Rooms
                    </a>

                    @if ($isAdmin || $isStaff)
                        <a href="{{ route('reservations.index') }}"
                            class="nav-link mb-1 block {{ request()->routeIs('reservations.index') ? 'active' : '' }}">
                            Manage Reservations
                        </a>
                    @endif

                    <a href="{{ route('reservations.create') }}"
                        class="nav-link mb-1 block {{ request()->routeIs('reservations.create') ? 'active' : '' }}">
                        New Reservation
                    </a>

                    @if ($isCustomer)
                        <a href="{{ route('summary.page') }}"
                            class="nav-link mb-1 block {{ request()->routeIs('summary.page') ? 'active' : '' }}">
                            My Summary
                        </a>
                    @endif

                    @if ($isAdmin)
                        <a href="{{ url('/api/rooms') }}" target="_blank"
                            class="nav-link mb-1 block">
                            API: Rooms JSON
                        </a>

                        <a href="{{ url('/api/reservations') }}" target="_blank"
                            class="nav-link mb-1 block">
                            API: Reservations JSON
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-red-700 px-3 py-2.5 text-left font-semibold transition hover:bg-red-800">Logout</button>
                    </form>
                </nav>
            @endauth
        </aside>

        <div>
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
    </div>
</body>

</html>
