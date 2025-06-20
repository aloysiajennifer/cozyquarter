<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>@yield('title') - CozyQuarter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ secure_asset('images/logo.jpeg') }}" type="image/jpeg">
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

<body class="bg-[var(--background-light)]  text-[var(--text-primary)] min-h-screen flex flex-col">


    <nav class="bg-[var(--primary)] fixed top-0 w-full z-50 shadow-md p-2">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <img class="h-14 w-auto rounded-4xl" src="{{ asset('images/logo.jpeg') }}" alt="CozyQuarter">
                </div>
            </div>
        </div>
        {{-- <div id="mobile-menu" class="sm:hidden hidden bg-[var(--primary)] pb-3">...</div> --}}
    </nav>

    <div class="flex-1 pt-16"> 
        <main>
            @yield('content')
        </main>
    </div>

     <footer class="bg-[var(--primary)] text-[var(--secondary)] py-6 mt-10 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} CozyQuarter. All rights reserved.</p>
            <p class="text-xs mt-1">Made with ❤️ for book lovers, coffee seekers, and space dreamers.</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mobileMenuButton = document.getElementById("mobile-menu-button");
            const mobileMenu = document.getElementById("mobile-menu");
            const menuIconPath = document.getElementById("menu-path");

            if (mobileMenuButton && mobileMenu && menuIconPath) {
                mobileMenuButton.addEventListener("click", function() {
                    mobileMenu.classList.toggle("hidden");
                    menuIconPath.setAttribute(
                        "d",
                        mobileMenu.classList.contains("hidden") ?
                        "M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" :
                        "M6 18L18 6M6 6l12 12"
                    );
                });
            }

            @if (session('auth_alert'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Access Denied',
                    text: "{{ session('auth_alert') }}",
                    confirmButtonColor: '#119DA4'
                });
            @endif

            
            window.confirmLogout = function() {
                Swal.fire({
                    title: 'Are you sure you want to log out?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, log out',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            };

            window.confirmLogoutMobile = function() {
                Swal.fire({
                    title: 'Are you sure you want to log out?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, log out',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form-mobile').submit();
                    }
                });
            };
        });
    </script>

</body>

</html>