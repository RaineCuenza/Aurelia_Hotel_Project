<?php

$searched_room = "";

$rooms = [
    "Standard" => [
        [
            "name" => "Standard Room 101",
            "image" => "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80",
            "price" => "₱1,500 / night",
            "description" => "A cozy room with a queen-sized bed, warm lighting, and a relaxing atmosphere."
        ],
        [
            "name" => "Standard Room 102",
            "image" => "https://images.unsplash.com/photo-1522798514-97ceb8c4f1c8?auto=format&fit=crop&w=900&q=80",
            "price" => "₱1,500 / night",
            "description" => "A comfortable room ideal for solo travelers or couples looking for affordability."
        ],
        [
            "name" => "Standard Room 103",
            "image" => "https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=900&q=80",
            "price" => "₱1,500 / night",
            "description" => "A simple and elegant room with modern furnishings and a peaceful stay experience."
        ]
    ],
    "Deluxe" => [
        [
            "name" => "Deluxe Room 201",
            "image" => "https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80",
            "price" => "₱2,500 / night",
            "description" => "A spacious deluxe room with premium bedding, stylish décor, and a city view."
        ],
        [
            "name" => "Deluxe Room 202",
            "image" => "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=900&q=80",
            "price" => "₱2,500 / night",
            "description" => "A refined room with upgraded amenities, a work desk, and enhanced comfort."
        ],
        [
            "name" => "Deluxe Room 203",
            "image" => "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80",
            "price" => "₱2,500 / night",
            "description" => "A bright and elegant room perfect for guests who want extra space and luxury."
        ]
    ],
    "Suite" => [
        [
            "name" => "Suite 301",
            "image" => "https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=900&q=80",
            "price" => "₱4,000 / night",
            "description" => "A luxury suite with a separate lounge area, premium interiors, and elevated comfort."
        ],
        [
            "name" => "Suite 302",
            "image" => "https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=900&q=80",
            "price" => "₱4,000 / night",
            "description" => "A sophisticated suite designed for families, long stays, and special occasions."
        ],
        [
            "name" => "Suite 303",
            "image" => "https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=900&q=80",
            "price" => "₱4,000 / night",
            "description" => "An exclusive suite featuring spacious interiors, luxury details, and a premium experience."
        ]
    ]
];

if (isset($_GET["room"])) {
    $searched_room = htmlspecialchars($_GET["room"]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Room - StayEase Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-6 py-4 lg:flex-row lg:px-10">

            <div class="text-2xl font-bold tracking-wide text-white">
                StayEase Hotel
            </div>

            <nav class="flex flex-wrap items-center justify-center gap-5 text-sm font-medium">

                <a href="dashboard.php" class="text-white hover:text-blue-300">Home</a>
                <a href="reserve.php" class="text-white hover:text-blue-300">Reserve</a>
                <a href="summary.php" class="text-white hover:text-blue-300">Summary</a>

                <a href="logout.php"
                    class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                    Logout
                </a>

            </nav>
        </div>
    </header>

    <main class="flex-grow">

        <!-- HERO -->
        <section class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-16 text-white lg:px-10">
            <div class="mx-auto max-w-5xl text-center">

                <h1 class="text-4xl font-bold md:text-5xl">
                    Search for a Room
                </h1>

                <p class="mt-4 text-blue-100">
                    Find the perfect room for your stay at StayEase Hotel.
                </p>

            </div>
        </section>

        <!-- SEARCH FORM -->
        <section class="px-6 py-16 lg:px-10">

            <div class="mx-auto max-w-3xl rounded-2xl bg-white p-8 shadow-lg md:p-10">

                <h2 class="text-center text-3xl font-bold text-slate-800">
                    Room Search
                </h2>

                <form method="GET" class="mt-8 space-y-5">

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Select Room Type
                        </label>

                        <select name="room"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="">Choose a room type</option>

                            <option value="Standard" <?php if ($searched_room == "Standard") echo "selected"; ?>>Standard</option>
                            <option value="Deluxe" <?php if ($searched_room == "Deluxe") echo "selected"; ?>>Deluxe</option>
                            <option value="Suite" <?php if ($searched_room == "Suite") echo "selected"; ?>>Suite</option>

                        </select>

                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-700 px-4 py-3 font-semibold text-white hover:bg-blue-800">
                        Search
                    </button>

                </form>

            </div>

        </section>

        <!-- ROOM RESULTS -->

        <?php if (!empty($searched_room) && isset($rooms[$searched_room])) { ?>

            <section class="px-6 pb-16 lg:px-10">

                <div class="mx-auto max-w-7xl">

                    <div class="mb-8 text-center">

                        <h3 class="text-3xl font-bold text-slate-800">
                            You've searched for: <?php echo $searched_room; ?>
                        </h3>

                    </div>

                    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($rooms[$searched_room] as $room) { ?>

                            <div class="overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-xl">

                                <img src="<?php echo $room["image"]; ?>" class="h-64 w-full object-cover">

                                <div class="p-6">

                                    <h4 class="text-xl font-bold text-slate-800">
                                        <?php echo $room["name"]; ?>
                                    </h4>

                                    <p class="mt-2 text-sm font-semibold text-blue-700">
                                        <?php echo $room["price"]; ?>
                                    </p>

                                    <p class="mt-3 text-slate-600">
                                        <?php echo $room["description"]; ?>
                                    </p>

                                    <a href="reserve.php?room=<?php echo $searched_room; ?>"
                                        class="mt-5 inline-block rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">
                                        Book This Room
                                    </a>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                </div>

            </section>

        <?php } ?>

    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-950 px-6 py-5 text-center text-sm text-slate-300">
        <p>&copy; <?php echo date("Y"); ?> StayEase Hotel. All rights reserved.</p>
    </footer>

</body>

</html>