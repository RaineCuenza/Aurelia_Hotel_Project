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
            background: linear-gradient(135deg, rgba(250, 248, 244, 0.75), rgba(245, 243, 239, 0.72)), url("{{ asset('images/login-bg.jpg') }}") center / cover no-repeat fixed;
            min-height: 100vh;
        }

        .brand-font {
            font-family: 'Cormorant Garamond', serif;
        }

        .brand-logo {
            width: 108px;
            height: 108px;
            margin: 0 auto;
            border-radius: 9999px;
            border: 2px solid rgba(185, 154, 97, 0.45);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
            background: #fff;
            object-fit: cover;
            object-position: center;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid rgba(185, 154, 97, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            backdrop-filter: none;
        }

        .btn-gold {
            background: linear-gradient(135deg, #b99a61, #9e814d);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
            color: #fff;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185, 154, 97, 0.25);
        }

        .login-input {
            border-radius: 12px;
            border: 1px solid rgba(185, 154, 97, 0.15);
            background: #ffffff;
            color: #2c2c2c;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        .login-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.1);
        }

        .login-input::placeholder {
            color: #d1d5db;
        }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center px-4">
    <div class="login-card w-full max-w-md rounded-2xl p-8">
        <img src="{{ asset('images/aurelia_logo.png') }}" alt="StayEase Hotel logo" class="brand-logo" />
        <h1 class="brand-font mt-3 text-center text-4xl font-bold text-gray-900">StayEase Hotel</h1>
        <p class="mt-2 text-center text-sm text-gray-600">Refined hospitality management access</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Email or Username</label>
                <input id="email" name="email" type="text" value="{{ old('email', 'admin@stayease.local') }}" required
                    class="login-input w-full px-3 py-2.5" />
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="login-input w-full px-3 py-2.5" />
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300"> Remember me
            </label>

            <button type="submit" class="btn-gold w-full rounded-xl px-4 py-2.5 font-semibold">
                Sign in
            </button>
        </form>

        <div class="mt-6 space-y-1 border-t border-gray-200 pt-4">
            <p class="text-center text-xs text-gray-500">Admin: admin@stayease.local / 123</p>
            <p class="text-center text-xs text-gray-500">Staff: staff@stayease.local / 123</p>
            <p class="text-center text-xs text-gray-500">Customer: customer@stayease.local / 123</p>
        </div>

        <p class="mt-4 text-center text-sm text-gray-600">
            New customer?
            <a href="{{ route('register') }}" class="font-semibold text-amber-800 hover:text-amber-700">Create an account</a>
        </p>
    </div>
</body>

</html>
