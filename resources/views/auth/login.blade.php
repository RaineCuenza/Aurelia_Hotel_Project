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

        body.loading-active {
            overflow: hidden;
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

        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 120;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(247, 242, 233, 0.72);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .page-loader.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .loader-card {
            width: min(92vw, 360px);
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.34);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 24px 40px rgba(42, 35, 24, 0.2);
            padding: 1.15rem 1rem 1rem;
            text-align: center;
        }

        .loader-ring {
            width: 48px;
            height: 48px;
            margin: 0 auto;
            border: 4px solid rgba(185, 154, 97, 0.22);
            border-top-color: #9e814d;
            border-radius: 9999px;
            animation: loader-spin 0.9s linear infinite;
        }

        .loader-title {
            margin-top: 0.8rem;
            font-size: 1.08rem;
            font-weight: 700;
            color: #2c2c2c;
        }

        .loader-message {
            margin-top: 0.28rem;
            font-size: 0.85rem;
            color: #6b7280;
        }

        @keyframes loader-spin {
            to {
                transform: rotate(360deg);
            }
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

    <div id="global-loader" class="page-loader" aria-hidden="true" aria-live="polite" aria-label="Loading">
        <div class="loader-card" role="status">
            <div class="loader-ring" aria-hidden="true"></div>
            <p class="loader-title">Please wait</p>
            <p id="global-loader-message" class="loader-message">Preparing your request...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('global-loader');
            const loaderMessage = document.getElementById('global-loader-message');
            let isLoaderVisible = false;

            const showLoader = (message) => {
                if (!loader || isLoaderVisible) {
                    return;
                }

                if (typeof message === 'string' && message.trim() !== '' && loaderMessage) {
                    loaderMessage.textContent = message;
                }

                loader.classList.add('active');
                loader.setAttribute('aria-hidden', 'false');
                document.body.classList.add('loading-active');
                isLoaderVisible = true;
            };

            const hideLoader = () => {
                if (!loader) {
                    return;
                }

                loader.classList.remove('active');
                loader.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('loading-active');
                isLoaderVisible = false;
            };

            document.querySelectorAll('form:not([data-no-loader])').forEach((form) => {
                form.addEventListener('submit', () => {
                    const message = form.dataset.loaderMessage || 'Signing you in...';
                    showLoader(message);
                });
            });

            document.addEventListener('click', (event) => {
                const anchor = event.target.closest('a[href]');

                if (!anchor || anchor.hasAttribute('data-no-loader') || anchor.target === '_blank') {
                    return;
                }

                const href = anchor.getAttribute('href') || '';
                if (href === '' || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
                    return;
                }

                const message = anchor.dataset.loaderMessage || 'Loading next page...';
                showLoader(message);
            });

            window.addEventListener('pageshow', hideLoader);
        });
    </script>
</body>

</html>
