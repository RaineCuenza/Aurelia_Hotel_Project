<?php
session_start();

$reservationExists = isset($_SESSION["reservation"]);

if ($reservationExists) {
    $data = $_SESSION["reservation"];

    $checkin = new DateTime($data["checkin"]);
    $checkout = new DateTime($data["checkout"]);

    $nights = $checkin->diff($checkout)->days;

    $prices = [
        "Standard" => 1500,
        "Deluxe" => 2500,
        "Suite" => 4000
    ];

    $price_per_night = $prices[$data["room"]];
    $total = $price_per_night * $nights;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary - StayEase Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-6 py-4 lg:flex-row lg:px-10">

            <div class="text-2xl font-bold tracking-wide text-white">
                StayEase Hotel
            </div>

            <nav class="flex flex-wrap items-center justify-center gap-5 text-sm font-medium">

                <a href="dashboard.php" class="text-white transition hover:text-blue-300">
                    Home
                </a>

                <a href="reserve.php" class="text-white transition hover:text-blue-300">
                    Reserve
                </a>

                <a href="search.php" class="text-white transition hover:text-blue-300">
                    Search Room
                </a>

                <a href="logout.php" class="rounded-md bg-red-600 px-4 py-2 text-white transition hover:bg-red-700">
                    Logout
                </a>

            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-16 text-white lg:px-10">

        <div class="mx-auto max-w-5xl text-center">

            <h1 class="text-4xl font-bold md:text-5xl">
                Booking Summary
            </h1>

            <p class="mt-4 text-base text-blue-100 md:text-lg">
                Review your reservation details and estimated total cost.
            </p>

        </div>

    </section>

    <!-- CONTENT -->
    <section class="px-6 py-16 lg:px-10">

        <div class="mx-auto max-w-4xl rounded-2xl bg-white p-8 shadow-lg md:p-10">

            <h2 class="text-center text-3xl font-bold text-slate-800">
                Reservation Details
            </h2>

            <?php if ($reservationExists) { ?>

                <div class="mt-8 grid gap-4 md:grid-cols-2">

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Full Name</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["name"]); ?>
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Email</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["email"]); ?>
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Check-in Date</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["checkin"]); ?>
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Check-out Date</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["checkout"]); ?>
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Room Type</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["room"]); ?>
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Guests</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["guests"]); ?>
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 md:col-span-2">
                        <p class="text-sm text-slate-500">Special Requests</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            <?php echo htmlspecialchars($data["requests"]); ?>
                        </p>
                    </div>

                </div>

                <!-- COST BREAKDOWN -->

                <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-6">

                    <h3 class="text-xl font-bold text-slate-800">
                        Cost Breakdown
                    </h3>

                    <div class="mt-4 space-y-3 text-slate-700">

                        <div class="flex items-center justify-between border-b border-blue-100 pb-3">
                            <span>Number of Nights</span>
                            <span class="font-semibold"><?php echo $nights; ?></span>
                        </div>

                        <div class="flex items-center justify-between border-b border-blue-100 pb-3">
                            <span>Price per Night</span>
                            <span class="font-semibold">₱<?php echo number_format($price_per_night, 2); ?></span>
                        </div>

                        <div class="flex items-center justify-between pt-1 text-lg">
                            <span class="font-bold text-slate-800">Total Cost</span>
                            <span class="font-bold text-blue-700">₱<?php echo number_format($total, 2); ?></span>
                        </div>

                    </div>

                </div>

            <?php } else { ?>

                <!-- NO BOOKING MESSAGE -->

                <div class="mt-8 text-center">

                    <p class="text-lg text-slate-600 mb-4">
                        Please book a room first before viewing your reservation.
                    </p>

                    <a href="reserve.php"
                        class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
                        Book a Room
                    </a>

                </div>

            <?php } ?>

            <!-- BUTTONS -->

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">

                <!-- <a href="reserve.php"
class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
Book Again
</a> -->

                <a href="dashboard.php"
                    class="rounded-lg bg-slate-600 px-6 py-3 font-semibold text-white transition hover:bg-slate-700">
                    Back to Dashboard
                </a>

            </div>

        </div>

    </section>

    <!-- FOOTER -->

    <footer class="bg-slate-950 px-6 py-5 text-center text-sm text-slate-300">
        <p>&copy; <?php echo date("Y"); ?> StayEase Hotel. All rights reserved.</p>
    </footer>

</body>

</html>