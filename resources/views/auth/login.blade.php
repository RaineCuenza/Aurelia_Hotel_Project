<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | StayEase Hotel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at 14% 10%, rgba(185, 154, 97, 0.17), transparent 32%),
                radial-gradient(circle at 86% 0%, rgba(185, 154, 97, 0.12), transparent 30%),
                #0d1623;
        }

        .brand-font {
            font-family: 'Cormorant Garamond', serif;
        }

        .login-card {
            background: rgba(16, 25, 38, 0.72);
            border: 1px solid rgba(185, 154, 97, 0.32);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(8px);
        }

        .btn-gold {
            background: linear-gradient(135deg, #b99a61, #9e814d);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(185, 154, 97, 0.28);
        }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4">
    <div class="login-card w-full max-w-md rounded-2xl p-8 text-white">
        <h1 class="brand-font text-center text-4xl font-semibold text-amber-50">StayEase Hotel</h1>
        <p class="mt-1 text-center text-sm text-slate-300">Refined hospitality management access</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-300/30 bg-red-500/20 px-4 py-3 text-sm text-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm text-slate-200">Email or Username</label>
                <input id="email" name="email" type="text" value="{{ old('email', 'admin@stayease.local') }}" required
                    class="w-full rounded-xl border border-slate-500/70 bg-slate-900/40 px-3 py-2.5 text-white placeholder:text-slate-400 focus:border-amber-300 focus:outline-none" />
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm text-slate-200">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-xl border border-slate-500/70 bg-slate-900/40 px-3 py-2.5 text-white placeholder:text-slate-400 focus:border-amber-300 focus:outline-none" />
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>

            <button type="submit" class="btn-gold w-full rounded-xl px-4 py-2.5 font-semibold text-white">
                Sign in
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-slate-300">Admin: admin@stayease.local / 123</p>
        <p class="mt-1 text-center text-xs text-slate-300">Staff: staff@stayease.local / 123</p>
        <p class="mt-1 text-center text-xs text-slate-300">Customer: customer@stayease.local / 123</p>
    </div>
</body>

</html>
