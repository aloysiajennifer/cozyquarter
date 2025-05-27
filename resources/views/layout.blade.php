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
        }
    </style>
</head>

<body class="bg-[var(--bg-light)] text-[var(--text-primary)] min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-[var(--primary)] fixed top-0 w-full z-50 shadow-md p-2">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <img class="h-14 w-auto rounded-4xl" src="/images/logo.jpeg" alt="CozyQuarter">
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <a href="{{ route('library.home') }}"
                        class="rounded-md px-3 py-2 text-sm font-medium 
                        {{ Route::currentRouteName() == 'library.home'
                            ? 'bg-[var(--accent-blue)] text-[var(--primary)]'
                            : 'text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white' }}">
                        Home
                    </a>
                    <a href=""
                        class="rounded-md px-3 py-2 text-sm font-medium 
                        {{ Route::currentRouteName() == ''
                            ? 'bg-[var(--accent-blue)] text-[var(--primary)]'
                            : 'text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white' }}">
                        Books Borrowed
                    </a>
                    <a href=""
                        class="rounded-md px-3 py-2 text-sm font-medium 
                        {{ Route::currentRouteName() == ''
                            ? 'bg-[var(--highlight)] text-[var(--primary)]'
                            : 'text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white' }}">
                        Co-Working Space
                    </a>
                    <a href=""
                        class="rounded-md px-3 py-2 text-sm font-medium 
                        {{ Route::currentRouteName() == ''
                            ? 'bg-[var(--highlight)] text-[var(--primary)]'
                            : 'text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white' }}">
                        Your Reservation
                    </a>
                    <a href=""
                        class="rounded-md px-3 py-2 text-sm font-medium 
                        {{ Route::currentRouteName() == ''
                            ? 'bg-[var(--highlight)] text-[var(--primary)]'
                            : 'text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white' }}">
                        Logout
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="sm:hidden">
                    <button id="mobile-menu-button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white focus:ring-2 focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg id="menu-icon" class="block w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path id="menu-path" stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <a href=""
                    class="block rounded-md px-3 py-2 text-sm font-medium text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white">Home</a>
                <a href=""
                    class="block rounded-md px-3 py-2 text-sm font-medium text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white">Books Borrowed</a>
                <a href=""
                    class="block rounded-md px-3 py-2 text-sm font-medium text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white">Co-Working
                    Space</a>
                <a href=""
                    class="block rounded-md px-3 py-2 text-sm font-medium text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white">Your
                    Reservation</a>
                <a href=""
                    class="block rounded-md px-3 py-2 text-sm font-medium text-[var(--secondary)] hover:bg-[var(--accent-green)] hover:text-white">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16 flex-1">
        <main>
            @yield('content')
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
