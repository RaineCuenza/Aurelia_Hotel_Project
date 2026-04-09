<?php
session_start();

$error = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars($_POST["user"]);
    $password = htmlspecialchars($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else if ($username == "admin" && $password == "123") {

        $_SESSION["user"] = $username;

        if (isset($_POST["remember"])) {
            setcookie("remembered_user", $username, time() + 3600);
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StayEase Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white flex flex-col">
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-10">

            <div class="text-2xl font-bold tracking-wide text-white">
                StayEase Hotel
            </div>

        </div>
    </header>
    <section
        class="relative flex flex-grow items-center justify-center overflow-hidden bg-cover bg-center px-4 py-10"
        style="background-image:
linear-gradient(rgba(15,23,42,0.55), rgba(15,23,42,0.55)),
url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1400&q=80');">

        <div class="absolute left-10 top-16 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-10 right-10 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-md rounded-3xl border border-white/30 bg-white/30 p-8 shadow-2xl backdrop-blur-lg">

            <h2 class="text-center text-3xl font-bold text-white">
                Welcome Back
            </h2>

            <p class="mt-3 text-center text-sm text-white/85">
                Sign in to manage your reservations and enjoy a luxury stay.
            </p>

            <form method="POST" autocomplete="off" class="mt-6 space-y-4">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <div>

                    <label class="mb-2 block text-sm font-medium text-white">
                        Username
                    </label>

                    <input
                        type="text"
                        name="user"
                        placeholder="Enter your username"
                        autocomplete="off"
                        value="<?php echo htmlspecialchars($username); ?>"
                        class="w-full rounded-xl border border-white/30 bg-white/15 px-4 py-3 text-white placeholder-white/60 outline-none focus:border-white/50 focus:bg-white/20 focus:ring-2 focus:ring-white/10">

                </div>

                <div>

                    <label class="mb-2 block text-sm font-medium text-white">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-white/30 bg-white/15 px-4 py-3 text-white placeholder-white/60 outline-none focus:border-white/50 focus:bg-white/20 focus:ring-2 focus:ring-white/10">

                </div>

                <div class="flex items-center justify-left gap-2 pt-1">

                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="h-4 w-4 rounded border-white/40 bg-white/20 text-blue-600 focus:ring-blue-500"
                        <?php echo isset($_COOKIE['remembered_user']) ? "checked" : ""; ?>>

                    <label for="remember" class="text-sm text-white/90">
                        Remember Me
                    </label>

                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-900/30 transition hover:-translate-y-0.5 hover:bg-blue-800">
                    Login
                </button>

            </form>

            <?php if ($error != "") { ?>

                <div class="mt-4 rounded-xl border border-red-200/20 bg-red-500/20 px-4 py-3 text-center text-sm font-medium text-red-100">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php } ?>

        </div>

    </section>
    <footer class="bg-slate-950 px-6 py-5 text-center text-sm text-slate-300">
        <p>&copy; <?php echo date("Y"); ?> StayEase Hotel. All rights reserved.</p>
    </footer>

</body>

</html>