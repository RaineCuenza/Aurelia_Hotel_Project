<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | StayEase Hotel</title>
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

        .register-card {
            background: #ffffff;
            border: 1px solid rgba(185, 154, 97, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }

        .form-input {
            border-radius: 12px;
            border: 1px solid rgba(185, 154, 97, 0.15);
            background: #ffffff;
            color: #2c2c2c;
        }

        .form-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.1);
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
    </style>
</head>

<body class="flex min-h-screen items-center justify-center px-4">
    <div class="register-card w-full max-w-md rounded-2xl p-8">
        <img src="{{ asset('images/aurelia_logo.png') }}" alt="StayEase Hotel logo" class="brand-logo" />
        <h1 class="brand-font mt-3 text-center text-4xl font-bold text-gray-900">Create Account</h1>
        <p class="mt-2 text-center text-sm text-gray-600">Join StayEase and start booking luxury stays.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.attempt') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="first_name">First Name</label>
                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required class="form-input w-full px-3 py-2.5">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="last_name">Last Name</label>
                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required class="form-input w-full px-3 py-2.5">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-input w-full px-3 py-2.5">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700" for="password">Password</label>
                <input id="password" name="password" type="password" required class="form-input w-full px-3 py-2.5">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input w-full px-3 py-2.5">
            </div>

            <button type="submit" class="btn-gold w-full rounded-xl px-4 py-2.5">Register</button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-amber-800 hover:text-amber-700">Sign in</a>
        </p>
    </div>
</body>

</html>
