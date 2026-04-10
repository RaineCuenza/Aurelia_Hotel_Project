@extends('layouts.app')

@section('content')
    <style>
        .hero-shell {
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid rgba(185, 154, 97, 0.2);
            background: #ffffff;
            box-shadow: 0 18px 36px rgba(34, 30, 22, 0.08);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            min-height: 460px;
        }

        .hero-image {
            background-image: linear-gradient(120deg, rgba(12, 16, 23, 0.18), rgba(12, 16, 23, 0.42)), url("{{ asset('images/login-bg.jpg') }}");
            background-size: cover;
            background-position: center;
        }

        .hero-content {
            background: linear-gradient(135deg, #fdfbf6 0%, #f5f1e8 100%);
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .booking-card {
            margin-top: 1.5rem;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid rgba(185, 154, 97, 0.18);
            box-shadow: 0 10px 24px rgba(41, 35, 24, 0.08);
            padding: 1rem;
        }

        .field-label {
            color: #8a7250;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e6ddcc;
            background: #fffdf9;
            padding: 0.72rem 0.78rem;
            color: #2b2b2b;
            margin-top: 0.35rem;
        }

        .field-input:focus {
            outline: none;
            border-color: #b99a61;
            box-shadow: 0 0 0 3px rgba(185, 154, 97, 0.12);
        }

        .btn-gold {
            border-radius: 12px;
            background: linear-gradient(135deg, #b99a61, #9e814d);
            color: #fff;
            font-weight: 700;
            padding: 0.78rem 1rem;
            width: 100%;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(185, 154, 97, 0.28);
        }

        .feature-card {
            border-radius: 16px;
            background: #fff;
            border: 1px solid rgba(185, 154, 97, 0.16);
            box-shadow: 0 8px 20px rgba(32, 25, 16, 0.05);
            padding: 1rem;
        }

        .metric-strip {
            margin-top: 1rem;
            border-radius: 16px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: linear-gradient(135deg, #fffefb 0%, #f8f3ea 100%);
            box-shadow: 0 10px 24px rgba(37, 31, 22, 0.05);
            padding: 1rem;
        }

        .metric-box {
            border-radius: 12px;
            border: 1px solid rgba(185, 154, 97, 0.13);
            background: #fff;
            padding: 0.9rem;
            text-align: center;
        }

        .highlight-shell {
            margin-top: 1.6rem;
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.16);
            background: linear-gradient(145deg, #fcfaf5 0%, #f4efe4 100%);
            box-shadow: 0 14px 28px rgba(34, 29, 21, 0.06);
            padding: 1.1rem;
        }

        .highlight-card {
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.14);
            background: #fff;
            box-shadow: 0 8px 20px rgba(34, 27, 18, 0.05);
            padding: 1rem;
        }

        .step-card {
            position: relative;
            border-radius: 14px;
            border: 1px solid rgba(185, 154, 97, 0.14);
            background: #fff;
            box-shadow: 0 8px 20px rgba(34, 27, 18, 0.05);
            padding: 1rem;
            padding-left: 1.15rem;
        }

        .step-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 14px 0 0 14px;
            background: linear-gradient(180deg, #c9ad78 0%, #9e814d 100%);
        }

        .cta-shell {
            margin-top: 1.6rem;
            border-radius: 18px;
            border: 1px solid rgba(185, 154, 97, 0.18);
            background: linear-gradient(130deg, #fff8ea 0%, #f0e4ca 100%);
            box-shadow: 0 14px 30px rgba(45, 35, 22, 0.08);
            padding: 1.4rem;
        }

        .btn-outline {
            border-radius: 12px;
            border: 1px solid rgba(79, 64, 39, 0.34);
            color: #4f4027;
            font-weight: 700;
            padding: 0.78rem 1rem;
            background: rgba(255, 255, 255, 0.72);
            transition: background 0.15s ease;
        }

        .btn-outline:hover {
            background: #fff;
        }

        .cta-primary {
            width: 100%;
            min-width: 17.5rem;
            padding: 1.15rem 2rem;
            font-size: 1.08rem;
            border-radius: 14px;
            box-shadow: 0 12px 24px rgba(185, 154, 97, 0.22);
        }

        .cta-primary:hover {
            box-shadow: 0 14px 28px rgba(185, 154, 97, 0.28);
        }

        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .hero-image {
                min-height: 260px;
            }
        }
    </style>

    <section class="hero-shell">
        <div class="hero-grid">
            <div class="hero-image"></div>

            <div class="hero-content">
                <p class="text-xs uppercase tracking-[0.22em] text-amber-800/90">Aurelia Hotel</p>
                <h1 class="section-title mt-2 text-5xl font-bold text-stone-900">Luxury Stay, Seamless Booking</h1>
                <p class="mt-3 text-sm leading-relaxed text-stone-600">
                    Reserve handcrafted rooms and suites designed for comfort and calm. Search availability in seconds and confirm your stay with confidence.
                </p>

                <form method="GET" action="{{ route('customer.search') }}" class="booking-card grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="field-label" for="check_in">Check-In</label>
                        <input id="check_in" name="check_in" type="date" class="field-input" required min="{{ now()->toDateString() }}" value="{{ old('check_in', now()->toDateString()) }}">
                    </div>

                    <div>
                        <label class="field-label" for="check_out">Check-Out</label>
                        <input id="check_out" name="check_out" type="date" class="field-input" required min="{{ now()->toDateString() }}" value="{{ old('check_out', now()->addDay()->toDateString()) }}">
                    </div>

                    <div>
                        <label class="field-label" for="guests">Guests</label>
                        <input id="guests" name="guests" type="number" min="1" max="12" value="2" class="field-input" required>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn-gold">Search Rooms</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="metric-strip">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <article class="metric-box">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Guest Rating</p>
                <p class="mt-1 text-3xl font-bold text-amber-800">4.9/5</p>
                <p class="text-xs text-stone-600">Based on verified stays</p>
            </article>
            <article class="metric-box">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Fast Booking</p>
                <p class="mt-1 text-3xl font-bold text-amber-800">2 min</p>
                <p class="text-xs text-stone-600">Average checkout time</p>
            </article>
            <article class="metric-box">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Premium Rooms</p>
                <p class="mt-1 text-3xl font-bold text-amber-800">{{ $featuredRoomTypes->sum('rooms_count') }}</p>
                <p class="text-xs text-stone-600">Carefully curated units</p>
            </article>
            <article class="metric-box">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Customer Care</p>
                <p class="mt-1 text-3xl font-bold text-amber-800">24/7</p>
                <p class="text-xs text-stone-600">Assistance at every step</p>
            </article>
        </div>
    </section>

    <section id="featured-rooms" class="mt-8 scroll-mt-24">
        <div class="mb-3 flex items-end justify-between">
            <div>
                <h2 class="section-title text-3xl font-bold text-stone-900">Featured Room Types</h2>
                <p class="mt-1 text-sm text-stone-600">Handpicked spaces for premium stays.</p>
            </div>
            <a href="{{ route('customer.search') }}" class="text-sm font-semibold text-amber-800 hover:text-amber-700">Browse All Rooms</a>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($featuredRoomTypes as $type)
                <article class="feature-card">
                    <p class="text-xs uppercase tracking-[0.16em] text-stone-500">{{ $type->rooms_count }} rooms</p>
                    <h3 class="section-title mt-2 text-3xl font-semibold text-stone-900">{{ $type->name }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm text-stone-600">{{ $type->description ?: 'Elegant accommodation tailored for comfort and convenience.' }}</p>
                    <p class="mt-4 text-lg font-bold text-amber-800">PHP {{ number_format((float) $type->base_price, 2) }} / night</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="highlight-shell">
        <div class="mb-3">
            <h2 class="section-title text-3xl font-bold text-stone-900">Why Guests Choose Aurelia</h2>
            <p class="mt-1 text-sm text-stone-600">A complete stay experience, from reservation to check-out.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <article class="highlight-card">
                <p class="text-xs uppercase tracking-[0.16em] text-amber-800">Elegant Ambience</p>
                <h3 class="section-title mt-1 text-2xl font-semibold text-stone-900">Refined Interiors</h3>
                <p class="mt-2 text-sm text-stone-600">Every room is crafted with warm textures, calming tones, and premium materials for restorative rest.</p>
            </article>
            <article class="highlight-card">
                <p class="text-xs uppercase tracking-[0.16em] text-amber-800">Smart Convenience</p>
                <h3 class="section-title mt-1 text-2xl font-semibold text-stone-900">Seamless Process</h3>
                <p class="mt-2 text-sm text-stone-600">Search by date, compare room types, and complete your reservation with instant confirmation.</p>
            </article>
            <article class="highlight-card">
                <p class="text-xs uppercase tracking-[0.16em] text-amber-800">Reliable Service</p>
                <h3 class="section-title mt-1 text-2xl font-semibold text-stone-900">Always Supported</h3>
                <p class="mt-2 text-sm text-stone-600">Our operations team keeps rooms guest-ready and supports your stay from arrival to departure.</p>
            </article>
        </div>
    </section>

    <section id="how-it-works" class="mt-7 scroll-mt-24">
        <div class="mb-3">
            <h2 class="section-title text-3xl font-bold text-stone-900">How Booking Works</h2>
            <p class="mt-1 text-sm text-stone-600">A simple 3-step flow for your next trip.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <article class="step-card">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Step 1</p>
                <h3 class="section-title mt-1 text-2xl font-semibold text-stone-900">Select Dates</h3>
                <p class="mt-2 text-sm text-stone-600">Choose your check-in and check-out to instantly see available rooms.</p>
            </article>
            <article class="step-card">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Step 2</p>
                <h3 class="section-title mt-1 text-2xl font-semibold text-stone-900">Pick Your Room</h3>
                <p class="mt-2 text-sm text-stone-600">Compare amenities and rates, then open the room details page.</p>
            </article>
            <article class="step-card">
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Step 3</p>
                <h3 class="section-title mt-1 text-2xl font-semibold text-stone-900">Confirm Booking</h3>
                <p class="mt-2 text-sm text-stone-600">Complete guest details, review total cost, and receive your reservation confirmation.</p>
            </article>
        </div>
    </section>

    <section id="about-us" class="highlight-shell scroll-mt-24">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-amber-800">About Us</p>
                <h2 class="section-title mt-2 text-4xl font-bold text-stone-900">Hospitality designed to feel calm, clear, and premium.</h2>
                <p class="mt-3 text-sm leading-relaxed text-stone-600">
                    StayEase Hotel is built around a simple idea: booking a stay should feel easy, refined, and trustworthy.
                    We combine thoughtful room curation, responsive service, and a streamlined reservation flow so guests can
                    focus on the trip, not the process.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-stone-600">
                    From the first search to check-out, every part of the experience is shaped to be efficient for guests and
                    manageable for our team. That balance is what keeps the experience polished and professional.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                <article class="highlight-card">
                    <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Focus</p>
                    <p class="mt-1 text-2xl font-bold text-amber-800">Guest-first</p>
                    <p class="mt-1 text-sm text-stone-600">A booking flow centered on clarity and confidence.</p>
                </article>
                <article class="highlight-card">
                    <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Standard</p>
                    <p class="mt-1 text-2xl font-bold text-amber-800">Professional</p>
                    <p class="mt-1 text-sm text-stone-600">A consistent presentation across booking and support.</p>
                </article>
                <article class="highlight-card">
                    <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Experience</p>
                    <p class="mt-1 text-2xl font-bold text-amber-800">Efficient</p>
                    <p class="mt-1 text-sm text-stone-600">Fast room discovery and simple reservation steps.</p>
                </article>
                <article class="highlight-card">
                    <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Support</p>
                    <p class="mt-1 text-2xl font-bold text-amber-800">Reliable</p>
                    <p class="mt-1 text-sm text-stone-600">Responsive service before, during, and after arrival.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="cta-shell">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between lg:gap-8">
            <div class="max-w-2xl">
                <p class="text-xs uppercase tracking-[0.18em] text-amber-900">Ready To Stay With Us</p>
                <h2 class="section-title mt-2 text-4xl font-bold text-stone-900">Book Your Next Luxury Escape</h2>
                <p class="mt-2 text-sm text-stone-700">Explore room availability now and secure the dates that fit your travel plans.</p>
            </div>
            <div class="w-full sm:w-auto lg:shrink-0 lg:self-center">
                <a href="{{ route('customer.search') }}" class="btn-gold cta-primary text-center">Start Booking</a>
            </div>
        </div>
    </section>
@endsection
