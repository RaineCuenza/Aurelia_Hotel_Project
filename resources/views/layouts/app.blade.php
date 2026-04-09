<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'StayEase Hotel' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    <header class="bg-slate-950 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-wide">StayEase Hotel</a>
            @auth
                <nav class="flex flex-wrap items-center gap-4 text-sm">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-300">Dashboard</a>
                    <a href="{{ route('rooms.search') }}" class="hover:text-blue-300">Search Rooms</a>
                    <a href="{{ route('reservations.index') }}" class="hover:text-blue-300">Reservations</a>
                    <a href="{{ route('reservations.create') }}" class="hover:text-blue-300">New Reservation</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="rounded bg-red-600 px-3 py-1.5 hover:bg-red-700">Logout</button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-5 py-8">
        @if (session('success'))
            <div class="mb-5 rounded border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded border border-red-300 bg-red-50 px-4 py-3 text-red-700">
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
</body>

</html>
