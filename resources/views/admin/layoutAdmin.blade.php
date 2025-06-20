<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Load Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <title>@yield('title', 'Admin Dashboard') - CozyQuarter</title>
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
            --medium-blue: #007BA7;
            /* Cerulean Blue */


            --primary-rgb: 28, 31, 38;
            --secondary-rgb: 208, 214, 214;
            --accent-green-rgb: 48, 227, 202;
            --accent-blue-rgb: 17, 157, 164;
            --highlight-rgb: 255, 215, 0;
            --medium-blue-rgb: 0, 123, 167;
        }

        #top-navbar .inline-flex:hover .fas,
        #top-navbar .inline-flex:focus .fas {
            color: var(--accent-blue);
        }

        .dark #top-navbar .inline-flex:hover .fas,
        .dark #top-navbar .inline-flex:focus .fas {
            color: var(--accent-green);
        }

        .sidebar-link-main:hover .sidebar-text,
        .sidebar-link-main:hover .fas,
        .sidebar-link-main:hover svg {
            color: var(--accent-blue);
        }

        .dark .sidebar-link-main:hover .sidebar-text,
        .dark .sidebar-link-main:hover .fas,
        .dark .sidebar-link-main:hover svg {
            color: var(--accent-green);
        }

        button[data-collapse-toggle]:hover .sidebar-text,
        button[data-collapse-toggle]:hover .fas,
        button[data-collapse-toggle]:hover svg {
            color: var(--accent-blue);
        }

        .dark button[data-collapse-toggle]:hover .sidebar-text,
        .dark button[data-collapse-toggle]:hover .fas,
        .dark button[data-collapse-toggle]:hover svg {
            color: var(--accent-green);
        }

        button[data-collapse-toggle][aria-controls^="dropdown-"]:hover .sidebar-text,
        button[data-collapse-toggle][aria-controls^="dropdown-"]:hover .fas {
            color: var(--accent-blue);
        }

        .dark button[data-collapse-toggle][aria-controls^="dropdown-"]:hover .sidebar-text,
        .dark button[data-collapse-toggle][aria-controls^="dropdown-"]:hover .fas {
            color: var(--accent-green);
        }

        .sidebar-link.pl-14:hover .sidebar-text,
        .sidebar-link.pl-14:hover .fas {
            color: var(--accent-blue);
        }

        .dark .sidebar-link.pl-14:hover .sidebar-text,
        .dark .sidebar-link.pl-14:hover .fas {
            color: var(--accent-green);
        }
    </style>
</head>

<body>
    <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden"></div>
    <nav id="top-navbar" class="z-40 fixed top-0 right-0 left-0 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="px-4 h-[67px]">
            <div class="flex items-center justify-between h-full">
                <div class="flex items-center">
                    <button id="sidebar-toggle-button" aria-controls="logo-sidebar" type="button" class="inline-flex p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <i class="fas fa-solid fa-list fa-lg"></i>
                        <span class="sr-only">Toggle sidebar</span>
                    </button>
                </div>

                <div class="flex items-center space-x-4 py-3">
                    @if(Auth::check() && Auth::user()->role_id == 2)
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">Admin</span>
                    @endif
                    <div class="flex items-center">
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'A') }}&background=119DA4&color=F5F7FA" alt="user photo">
                        </button>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user" data-dropdown-placement="bottom-end">
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-300">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            <ul class="py-1">
                                <li><a href="{{route('dashboard')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200">Dashboard</a></li>
                                <li>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="button" onclick="confirmLogout()" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 transition-transform -translate-x-full sm:translate-x-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700" aria-label="Sidebar">
        <div class="flex items-center justify-between px-4 h-[68px] border-b border-gray-200 dark:border-gray-700">
            <a href="#" class="flex items-center">
                <img src="{{ asset('images/logo.jpeg') }}" class="h-10 me-3 rounded-full" alt="CozyQuarter Logo" />
                <span class="sidebar-text self-center text-xl font-semibold whitespace-nowrap dark:text-white">COZY QUARTER</span>
            </a>
            <button id="sidebar-close-button" type="button" class="p-1 text-gray-400 rounded-md hover:text-gray-900 dark:hover:text-white sm:hidden">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-[calc(100vh-68px)] px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium pt-4">
                <li>
                    <a href="{{route('dashboard')}}" class="flex items-center p-2 rounded-lg group sidebar-link-main hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-chart-bar fa-lg text-[var(--primary)] dark:text-[var(--secondary)] "></i>
                        <span class="sidebar-text ms-3 text-[var(--primary)] dark:text-[var(--secondary)] ">Dashboard</span>
                    </a>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-300" aria-controls="dropdown-management" data-collapse-toggle="dropdown-management">
                        <i class="fas fa-solid fa-folder-open fa-lg text-[var(--primary)] dark:text-[var(--secondary)] "></i>
                        <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap text-[var(--primary)] dark:text-[var(--secondary)] ">Management</span>
                        <svg class="sidebar-text w-3 h-3 text-[var(--primary)] dark:text-[var(--secondary)]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-management" class="hidden py-2 space-y-2">
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 pl-8 text-gray-900 dark:text-gray-300" aria-controls="dropdown-library" data-collapse-toggle="dropdown-library">
                                <i class="fas fa-book-reader fa-sm mr-2 text-[var(--primary)] dark:text-[var(--secondary)]"></i> <span class="sidebar-text flex-1 text-left whitespace-nowrap text-[var(--primary)] dark:text-[var(--secondary)]">Library</span>
                                <svg class="sidebar-text w-3 h-3 text-[var(--primary)] dark:text-[var(--secondary)]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-library" class="hidden py-2 space-y-2">
                                <li><a href="{{route('book.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('book.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-book-open fa-sm mr-2"></i> Book
                                    </a></li>
                                <li><a href="{{route('category.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('category.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-tags fa-sm mr-2"></i> Category
                                    </a></li>
                                <li><a href="{{route('shelf.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('shelf.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-warehouse fa-sm mr-2"></i> Shelf
                                    </a></li>
                                <li><a href="{{route('borrowing.index')}}" class=" sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('borrowing.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-exchange-alt fa-sm mr-2"></i> Borrowing
                                    </a></li>
                            </ul>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 pl-8 text-gray-900 dark:text-gray-300" aria-controls="dropdown-cwspace" data-collapse-toggle="dropdown-cwspace">
                                <i class="fas fa-desktop fa-sm mr-2 text-[var(--primary)] dark:text-[var(--secondary)]"></i> <span class="sidebar-text flex-1 text-left whitespace-nowrap text-[var(--primary)] dark:text-[var(--secondary)]">CW Space</span>
                                <svg class="sidebar-text w-3 h-3 text-[var(--primary)] dark:text-[var(--secondary)]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-cwspace" class="hidden py-2 space-y-2">
                                <li><a href="{{route('cwspace.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('cwspace.*')) active @endif text-gray-900 dark:text-gray-300">
                                        <i class="fas fa-laptop-code fa-sm mr-2"></i> CW Space
                                    </a></li>
                                <li><a href="{{route('schedule.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('schedule.*')) active @endif text-gray-900 dark:text-gray-300">
                                        <i class="fas fa-calendar-alt fa-sm mr-2"></i> Schedule
                                    </a></li>
                                <li><a href="{{route('reservation.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('reservation.*')) active @endif text-gray-900 dark:text-gray-300">
                                        <i class="fas fa-clipboard-list fa-sm mr-2"></i> Reservation
                                    </a></li>
                            </ul>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 pl-8 text-gray-900 dark:text-gray-300" aria-controls="dropdown-cafe" data-collapse-toggle="dropdown-cafe">
                                <i class="fas fa-coffee fa-sm mr-2 text-[var(--primary)] dark:text-[var(--secondary)]"></i> <span class="sidebar-text flex-1 text-left whitespace-nowrap text-[var(--primary)] dark:text-[var(--secondary)]">Cafe</span>
                                <svg class="sidebar-text w-3 h-3 text-[var(--primary)] dark:text-[var(--secondary)]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-cafe" class="hidden py-2 space-y-2">
                                <li><a href="{{route('beverage.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('beverage.*')) active @endif text-gray-900 dark:text-gray-300">
                                        <i class="fas fa-mug-hot fa-sm mr-2"></i> Beverage
                                    </a></li>
                                <li><a href="{{route('order.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('order.*')) active @endif text-gray-900 dark:text-gray-300">
                                        <i class="fas fa-shopping-cart fa-sm mr-2"></i> Order
                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300" aria-controls="dropdown-reports" data-collapse-toggle="dropdown-reports">
                        <i class="fas fa-solid fa-book fa-lg text-[var(--primary)] dark:text-[var(--secondary)] "></i>
                        <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap text-[var(--primary)] dark:text-[var(--secondary)]">Reports</span>
                        <svg class="sidebar-text w-3 h-3 text-[var(--primary)] dark:text-[var(--secondary)]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-reports" class="hidden py-2 space-y-2">
                        <li>
                            <a href="{{route('report.borrowing')}}"
                                class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('borrowing.*')) active @endif text-gray-900 dark:text-gray-300">
                                <i class="fas fa-file-alt fa-sm mr-2"></i> Borrowing Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('report.orderDrink') }}"
                                class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('report.orderDrink')) active @endif text-gray-900 dark:text-gray-300">
                                <i class="fas fa-file-invoice-dollar fa-sm mr-2"></i> Drink Orders Report
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </aside>

    <main id="main-content" class="p-4 transition-all duration-300 ease-in-out sm:ml-64 dark:bg-gray-800">
        <div>
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('logo-sidebar');
            const mainContent = document.getElementById('main-content');
            const topNavbar = document.getElementById('top-navbar');
            const toggleButton = document.getElementById('sidebar-toggle-button');
            const closeButton = document.getElementById('sidebar-close-button');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            const allDropdowns = document.querySelectorAll('aside ul[id^="dropdown-"]');

            const smBreakpoint = 640;

            const getSidebarState = () => localStorage.getItem('sidebarState_desktop');
            const setSidebarState = (state) => localStorage.setItem('sidebarState_desktop', state);
            const isDesktop = () => window.innerWidth >= smBreakpoint;

            // const closeAllDropdowns = () => {
            //     allDropdowns.forEach(dropdown => {
            //         const trigger = document.querySelector(`[data-collapse-toggle="${dropdown.id}"]`);
            //         if (trigger && !dropdown.classList.contains('hidden')) {
            //             const collapse = new Collapse(dropdown, trigger);
            //             collapse.hide();
            //         }
            //     });
            // };

            const applyDesktopState = (state) => {
                const isCollapsed = state === 'collapsed';
                sidebar.classList.toggle('w-15', isCollapsed);
                sidebar.classList.toggle('w-64', !isCollapsed);
                mainContent.classList.toggle('sm:ml-20', isCollapsed);
                mainContent.classList.toggle('sm:ml-64', !isCollapsed);
                topNavbar.classList.toggle('sm:left-20', isCollapsed);
                topNavbar.classList.toggle('sm:left-64', !isCollapsed);
                sidebarTexts.forEach(text => text.classList.toggle('hidden', isCollapsed));
                // if (isCollapsed) closeAllDropdowns();
                // document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
                //     btn.disabled = isCollapsed;
                // });
            };

            const applyMobileState = (show) => {
                sidebar.classList.toggle('translate-x-0', show);
                sidebar.classList.toggle('-translate-x-full', !show);
                sidebarBackdrop.classList.toggle('hidden', !show);
            };

            const updateSidebarLayout = () => {
                if (isDesktop()) {
                    applyMobileState(false);
                    const state = getSidebarState() || 'expanded';
                    applyDesktopState(state);
                } else {
                    applyDesktopState('expanded');
                    sidebar.classList.remove('w-20');
                    mainContent.classList.remove('sm:ml-20');
                    topNavbar.classList.remove('sm:left-20');
                    applyMobileState(false);
                }
            };

            toggleButton.addEventListener('click', function() {
                if (isDesktop()) {
                    const newState = (getSidebarState() === 'collapsed') ? 'expanded' : 'collapsed';
                    setSidebarState(newState);
                    applyDesktopState(newState);
                    if (newState === 'collapsed') {
                        closeAllDropdowns(); // Pindahkan ke sini
                    }
                } else {
                    applyMobileState(true);
                }
            });

            closeButton.addEventListener('click', () => applyMobileState(false));
            sidebarBackdrop.addEventListener('click', () => applyMobileState(false));

            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    const scrollPos = window.scrollY;
                    sessionStorage.setItem('lastScrollPosition', scrollPos);
                });
            });

            window.addEventListener('resize', updateSidebarLayout);
            updateSidebarLayout();

            const savedScrollPos = sessionStorage.getItem('lastScrollPosition');
            if (savedScrollPos !== null) {
                window.scrollTo(0, parseInt(savedScrollPos));
                sessionStorage.removeItem('lastScrollPosition');
            }

            // SWITCH THEME
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            function applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    // themeToggleLightIcon.classList.remove('hidden');
                    // themeToggleDarkIcon.classList.add('hidden'); 
                } else {
                    document.documentElement.classList.remove('dark');
                    // themeToggleDarkIcon.classList.remove('hidden'); 
                    // themeToggleLightIcon.classList.add('hidden'); 
                }
            }
            const currentTheme = localStorage.getItem('color-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyTheme(currentTheme);
            // themeToggleBtn.addEventListener('click', function() { 
            //     const newTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            //     localStorage.setItem('color-theme', newTheme);
            //     applyTheme(newTheme);
            // });
        });

        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#30E3CA',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>

</html>