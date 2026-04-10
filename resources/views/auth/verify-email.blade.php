<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Aurelia Hotel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background: linear-gradient(135deg, rgba(250, 248, 244, 0.76), rgba(245, 243, 239, 0.72)), url("{{ asset('images/login-bg.jpg') }}") center / cover no-repeat fixed;
            min-height: 100vh;
        }

        .brand-font {
            font-family: 'Cormorant Garamond', serif;
        }

        .verify-card {
            background: #ffffff;
            border: 1px solid rgba(185, 154, 97, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }

        .brand-logo {
            width: 96px;
            height: 96px;
            margin: 0 auto;
            border-radius: 9999px;
            border: 2px solid rgba(185, 154, 97, 0.45);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
            background: #fff;
            object-fit: cover;
            object-position: center;
        }

        .btn-gold {
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
            font-weight: 700;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(185, 154, 97, 0.25);
        }

        .btn-outline {
            border: 1px solid rgba(185, 154, 97, 0.28);
            color: #7b5f2b;
            font-weight: 700;
            transition: background-color 0.16s ease;
        }

        .btn-outline:hover {
            background: rgba(185, 154, 97, 0.08);
        }
    </style>
</head>

<body class="flex min-h-screen items-center justify-center px-4">
    <div class="verify-card w-full max-w-lg rounded-2xl p-8">
        <img src="{{ asset('images/aurelia_logo.png') }}" alt="Aurelia Hotel logo" class="brand-logo" />
        <h1 class="brand-font mt-3 text-center text-4xl font-bold text-gray-900">Verify Your Email</h1>
        <p class="mt-3 text-center text-sm text-gray-600">
            Before booking, please verify your email address by clicking the link we sent to
            <span class="font-semibold text-gray-800">{{ auth()->user()->email }}</span>.
        </p>

        @if (session('success'))
            <div class="mt-5 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-gold w-full rounded-xl px-4 py-2.5">Resend Verification</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-outline w-full rounded-xl px-4 py-2.5">Sign Out</button>
            </form>
        </div>
    </div>
</body>

</html>
