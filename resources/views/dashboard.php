<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StayEase Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/90 backdrop-blur-md">

        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-6 py-4 lg:flex-row lg:px-10">

            <div class="text-2xl font-bold tracking-wide text-white">
                StayEase Hotel
            </div>

            <nav class="flex flex-wrap items-center justify-center gap-5 text-sm font-medium">

                <a href="search.php" class="text-white hover:text-blue-300">
                    Search Room
                </a>

                <!-- USER DROPDOWN -->
                <div class="relative">

                    <button onclick="toggleMenu()" class="flex items-center gap-2 text-white hover:text-blue-300">

                        <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>

                        <!-- Chevron Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7" />

                        </svg>

                    </button>

                    <!-- Dropdown -->
                    <div id="userMenu"
                        class="hidden absolute right-0 mt-2 w-32 rounded-md bg-white shadow-lg">

                        <a href="logout.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">

                            Logout

                        </a>

                    </div>

                </div>

            </nav>

        </div>

    </header>

    <!-- HERO SECTION -->
    <section
        class="relative flex min-h-[95vh] items-center bg-cover bg-center"
        style="background-image:
linear-gradient(rgba(15,23,42,0.55), rgba(15,23,42,0.55)),
url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1400&q=80');">

        <div class="w-full px-6 py-16 lg:px-10">

            <div class="mx-auto max-w-3xl text-center text-white">

                <h1 class="text-4xl font-bold md:text-6xl">
                    Luxury Stay, Comfort, <br> and Elegance
                </h1>

                <p class="mx-auto mt-5 max-w-2xl text-base text-slate-100 md:text-lg">
                    Discover premium rooms, exceptional dining, and relaxing amenities
                    crafted for a memorable stay at StayEase Hotel.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">

                    <a href="reserve.php"
                        class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">

                        Book Now

                    </a>

                    <a href="summary.php"
                        class="rounded-lg bg-slate-600 px-6 py-3 font-semibold text-white hover:bg-slate-700">

                        View Booking Summary

                    </a>

                </div>

            </div>

        </div>

    </section>

    <section class="bg-white px-6 py-20 lg:px-10">
        <div class="mx-auto max-w-5xl text-center">
            <h2 class="text-3xl font-bold text-slate-800">Why Choose StayEase Hotel?</h2>
            <p class="mx-auto mt-5 max-w-3xl leading-8 text-slate-600">
                We combine elegant accommodations, personalized hospitality, and modern comforts
                to deliver a luxurious experience for every guest.
            </p>
        </div>
    </section>

    <section id="rooms" class="bg-slate-50 px-6 py-20 lg:px-10">
        <div class="mx-auto max-w-6xl">
            <h2 class="text-center text-3xl font-bold text-slate-800">Our Rooms</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Standard Room</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Comfortable and stylish, perfect for solo travelers and couples.
                    </p>
                    <p class="mt-4 font-semibold text-slate-800">Price: ₱1,500 / night</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Deluxe Room</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Spacious room with upgraded amenities for a premium experience.
                    </p>
                    <p class="mt-4 font-semibold text-slate-800">Price: ₱2,500 / night</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Suite</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Luxury suite ideal for families, longer stays, and special occasions.
                    </p>
                    <p class="mt-4 font-semibold text-slate-800">Price: ₱4,000 / night</p>
                </div>
            </div>
        </div>
    </section>

    <section id="amenities" class="bg-white px-6 py-20 lg:px-10">
        <div class="mx-auto max-w-6xl">
            <h2 class="text-center text-3xl font-bold text-slate-800">Hotel Amenities</h2>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Infinity Pool</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Enjoy a relaxing swim with a scenic and calming atmosphere.
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Fine Dining</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Savor local and international cuisine prepared by our chefs.
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Spa & Wellness</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Refresh your body and mind with our spa treatments and massage services.
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-xl font-semibold text-slate-900">Fast Wi-Fi</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        Stay connected with reliable internet access throughout the hotel.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="offers" class="bg-blue-50 px-6 py-20 lg:px-10">
        <div class="mx-auto max-w-5xl">
            <h2 class="text-center text-3xl font-bold text-slate-800">Special Offers</h2>
            <div class="mx-auto mt-10 max-w-4xl rounded-xl border-l-4 border-blue-600 bg-white p-6 shadow-md">
                <p class="leading-8 text-slate-700">
                    <span class="font-semibold">Weekend Escape:</span>
                    Stay for 2 nights and get 10% off your total booking.
                </p>
                <p class="mt-3 leading-8 text-slate-700">
                    <span class="font-semibold">Family Getaway:</span>
                    Free breakfast for children with every Suite reservation.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 px-6 py-20 text-white lg:px-10">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="text-3xl font-bold">Plan your next stay with us</h2>
            <p class="mx-auto mt-4 max-w-2xl leading-8 text-slate-200">
                Reserve now and enjoy comfort, elegance, and memorable hospitality.
            </p>
            <a href="reserve.php" class="mt-8 inline-block rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
                Make a Reservation
            </a>
        </div>
    </section>

    <section id="contact" class="bg-slate-50 px-6 py-20 text-center lg:px-10">
        <div class="mx-auto max-w-4xl">
            <h2 class="text-3xl font-bold text-slate-800">Contact Us</h2>
            <div class="mt-6 space-y-2 text-slate-600">
                <p>Email: reservations@StayEasebayhotel.com</p>
                <p>Phone: +63 912 345 6789</p>
                <p>Address: Seaside Avenue, Manila, Philippines</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 px-6 py-5 text-center text-sm text-slate-300">

        <p>&copy; <?php echo date("Y"); ?> StayEase Hotel. All rights reserved.</p>

    </footer>

    <!-- SCRIPT FOR DROPDOWN -->
    <script>
        function toggleMenu() {
            const menu = document.getElementById("userMenu");
            menu.classList.toggle("hidden");
        }
    </script>

</body>

</html>