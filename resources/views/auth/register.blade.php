<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logo.jpeg') }}" type="image/jpeg">
    <style>
        :root {
            --primary: #1C1F26;
            /* Dark Gunmetal */
            --secondary: #D0D6D6;
            /* Cool Gray */
            --accent-green: #30E3CA;
            /* Neon Mint */
            --accent-blue: #119DA4;
            /* Deep Teal Blue */
            --background-light: #F5F7FA;
            /* Pale Sky Gray */
            --text-primary: #0F0F0F;
            /* Jet Black */
            --highlight: #FFD700;
            /* Cyber Gold */
            --medium-blue: #007BA7;
            /* Cerulean Blue */
        }
    </style>
</head>

<body class="bg-[var(--background-light)] text-[var(--text-primary)] min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-[var(--primary)] top-0 w-full z-50 shadow-md p-2">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <img class="h-14 w-auto rounded-4xl" src="/images/logo.jpeg" alt="CozyQuarter">
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16 flex-1">
        <main>
            <div class="flex items-center justify-center min-h-screen bg-[var(--background-light)] px-4">
                <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
                    <div class="flex flex-col items-center mb-6">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Perpustakaan" 
                            class="w-28 h-28 mb-4 rounded-full border-4 border-[var(--accent-blue)]">
                        <h2 class="text-2xl font-bold text-[var(--primary)]">Daftar CozyQuarter</h2>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <input type="text" name="name" placeholder="Name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                            value="{{ old('name') }}">

                        <input type="text" name="username" placeholder="Username" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                            value="{{ old('username') }}">

                        <input type="text" name="phone" placeholder="Phone" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                            value="{{ old('phone') }}">

                        <input type="email" name="email" placeholder="Email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                            value="{{ old('email') }}">

                        <input type="password" name="password" placeholder="Password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]">

                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]">

                        @if ($errors->any())
                            <div class="text-red-600 text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit"
                            class="w-full bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] text-white font-semibold py-2 rounded-lg transition duration-200">
                            Register
                        </button>
                    </form>

                    <p class="text-sm text-center mt-4 text-[var(--primary)]">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[var(--accent-blue)] hover:underline">Login</a>
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-[var(--primary)] text-[var(--secondary)] py-6 mt-10 shadow-inner">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-sm">&copy; 2025 CozyQuarter. All rights reserved.</p>
                <p class="text-xs mt-1">Made with ❤️ for book lovers, coffee seekers, and space dreamers.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuButton = document.getElementById("mobile-menu-button");
            const mobileMenu = document.getElementById("mobile-menu");
            const menuIconPath = document.getElementById("menu-path");

            menuButton.addEventListener("click", function() {
                mobileMenu.classList.toggle("hidden");
                menuIconPath.setAttribute(
                    "d",
                    mobileMenu.classList.contains("hidden") ?
                    "M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" :
                    "M6 18L18 6M6 6l12 12"
                );
            });
        });
    </script>
</body>
</html>



