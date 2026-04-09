<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | StayEase Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4">
    <div class="w-full max-w-md rounded-2xl border border-white/20 bg-white/10 p-8 text-white shadow-2xl backdrop-blur">
        <h1 class="text-center text-3xl font-bold">StayEase Login</h1>
        <p class="mt-2 text-center text-sm text-slate-200">Use your admin account to continue.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-300/30 bg-red-500/20 px-4 py-3 text-sm text-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm">Email or Username</label>
                <input id="email" name="email" type="text" value="{{ old('email', 'admin@stayease.local') }}" required
                    class="w-full rounded-lg border border-white/30 bg-white/15 px-3 py-2 text-white placeholder:text-slate-300 focus:border-blue-300 focus:outline-none" />
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border border-white/30 bg-white/15 px-3 py-2 text-white placeholder:text-slate-300 focus:border-blue-300 focus:outline-none" />
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>

            <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold hover:bg-blue-700">
                Sign in
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-slate-300">Default: admin@stayease.local / 123</p>
    </div>
</body>

</html>
