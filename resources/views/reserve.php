<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

$selectedRoom = "";

if (isset($_GET["room"])) {
    $selectedRoom = $_GET["room"];
}


$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];
    $room = $_POST["room"];
    $guests = $_POST["guests"];
    $requests = htmlspecialchars($_POST["requests"]);

    if (empty($name) || empty($email) || empty($checkin) || empty($checkout) || empty($room) || empty($guests)) {
        $error = "Please fill all required fields.";
    } else {

        $_SESSION["reservation"] = [
            "name" => $name,
            "email" => $email,
            "checkin" => $checkin,
            "checkout" => $checkout,
            "room" => $room,
            "guests" => $guests,
            "requests" => $requests
        ];

        header("Location: summary.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Room - StayEase Hotel</title>
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

                <a href="dashboard.php" class="text-white hover:text-blue-300">
                    Home
                </a>

                <a href="search.php" class="text-white hover:text-blue-300">
                    Search Room
                </a>

                <a href="summary.php" class="text-white hover:text-blue-300">
                    Summary
                </a>

                <a href="logout.php"
                    class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                    Logout
                </a>

            </nav>

        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-16 text-white lg:px-10">

        <div class="mx-auto max-w-5xl text-center">

            <h1 class="text-4xl font-bold md:text-5xl">
                Reserve Your Stay
            </h1>

            <p class="mt-4 text-blue-100">
                Complete the form below to book your preferred room at StayEase Hotel.
            </p>

        </div>

    </section>

    <!-- FORM SECTION -->
    <section class="px-6 py-16 lg:px-10">

        <div class="mx-auto max-w-3xl rounded-2xl bg-white p-8 shadow-lg md:p-10">

            <h2 class="text-center text-3xl font-bold text-slate-800">
                Reservation Form
            </h2>

            <?php if ($error != "") { ?>

                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-4 text-red-700">
                    <?php echo $error; ?>
                </div>

            <?php } ?>

            <form method="POST" class="mt-8 space-y-5">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <!-- FULL NAME -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Email
                    </label>

                    <input
                        type="text"
                        name="email"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                <!-- CHECKIN CHECKOUT -->
                <div class="grid gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Check-in Date
                        </label>

                        <input
                            type="date"
                            name="checkin"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Check-out Date
                        </label>

                        <input
                            type="date"
                            name="checkout"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>

                </div>

                <!-- ROOM + GUESTS -->
                <div class="grid gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Room Type
                        </label>

                        <select
                            name="room"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="">Select Room Type</option>

                            <option value="Standard" <?php if ($selectedRoom == "Standard") echo "selected"; ?>>
                                Standard
                            </option>

                            <option value="Deluxe" <?php if ($selectedRoom == "Deluxe") echo "selected"; ?>>
                                Deluxe
                            </option>

                            <option value="Suite" <?php if ($selectedRoom == "Suite") echo "selected"; ?>>
                                Suite
                            </option>

                        </select>

                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Number of Guests
                        </label>

                        <input
                            type="number"
                            name="guests"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    </div>

                </div>

                <!-- REQUESTS -->
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Special Requests
                    </label>

                    <textarea
                        name="requests"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>

                </div>

                <!-- BUTTON -->

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-700 px-4 py-3 font-semibold text-white shadow-md hover:bg-blue-800">
                    Submit Reservation
                </button>

            </form>

        </div>

    </section>

    <!-- FOOTER -->

    <footer class="bg-slate-950 px-6 py-5 text-center text-sm text-slate-300">
        <p>&copy; <?php echo date("Y"); ?> StayEase Hotel. All rights reserved.</p>
    </footer>

</body>

</html>