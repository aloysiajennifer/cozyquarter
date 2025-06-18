<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Load Tailwind CSS dulu -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Load Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Load Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Load SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Load Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- Load Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Load Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <title>@yield('title', 'Admin Dashboard') - CozyQuarter</title>
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

<body>
    <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden"></div>
    <nav id="top-navbar" class="z-40 fixed top-0 right-0 left-0 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="px-4 h-[67px]">
            <div class="flex items-center justify-between h-full">
                <div class="flex items-center">
                    <button id="sidebar-toggle-button" aria-controls="logo-sidebar" type="button" class="inline-flex p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Toggle sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center space-x-4 py-3">
                    <!-- Switch mode -->
                    <!-- <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="hidden w-5 h-5">
                            <path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM7.5 12a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM18.894 6.166a.75.75 0 0 0-1.06-1.06l-1.591 1.59a.75.75 0 1 0 1.06 1.061l1.591-1.59ZM21.75 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5H21a.75.75 0 0 1 .75.75ZM17.834 18.894a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 1 0-1.061 1.06l1.59 1.591ZM12 18a.75.75 0 0 1 .75.75V21a.75.75 0 0 1-1.5 0v-2.25A.75.75 0 0 1 12 18ZM7.758 17.303a.75.75 0 0 0-1.061-1.06l-1.591 1.59a.75.75 0 0 0 1.06 1.061l1.591-1.59ZM6 12a.75.75 0 0 1-.75.75H3a.75.75 0 0 1 0-1.5h2.25A.75.75 0 0 1 6 12ZM6.697 7.757a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.59 1.591Z" />
                        </svg>
                    </button> -->
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
                                <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200">Dashboard</a></li>
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
                <img src="{{ asset('images/logo.jpeg') }}" class="h-10 me-3 rounded-md" alt="CozyQuarter Logo" />
                <span class="sidebar-text self-center text-xl font-semibold whitespace-nowrap dark:text-white">COZY QUARTER</span>
            </a>
            <button id="sidebar-close-button" type="button" class="p-1 text-gray-400 rounded-md hover:text-gray-900 dark:hover:text-white sm:hidden">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium pt-4">
                <li>
                    <a href="{{route('dashboards')}}" class="flex items-center p-2 rounded-lg group sidebar-link text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white dark:text-gray-400" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="sidebar-text ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-300" aria-controls="dropdown-management" data-collapse-toggle="dropdown-management">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 18.375V5.625ZM21 9.375A.375.375 0 0 0 20.625 9h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5ZM10.875 18.75a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5ZM3.375 15h7.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375Zm0-3.75h7.5a.375.375 0 0 0 .375-.375v-1.5A.375.375 0 0 0 10.875 9h-7.5A.375.375 0 0 0 3 9.375v1.5c0 .207.168.375.375.375Z" clip-rule="evenodd" />
                        </svg>
                        <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap">Management</span>
                        <svg class="sidebar-text w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-management" class="hidden py-2 space-y-2">
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 pl-8 text-gray-900 dark:text-gray-300" aria-controls="dropdown-library" data-collapse-toggle="dropdown-library">
                                <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap">Library</span>
                                <svg class="sidebar-text w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-library" class="hidden py-2 space-y-2">
                                <li><a href="{{route('book.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('book.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Book</a></li>
                                <li><a href="{{route('category.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('category.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Category</a></li>
                                <li><a href="{{route('shelf.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('shelf.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Shelf</a></li>
                                <li><a href="{{route('borrowing.index')}}" class=" sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group sidebar-link @if(request()->routeIs('borrowing.*')) active @endif text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Borrowing</a></li>
                            </ul>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 pl-8 text-gray-900 dark:text-gray-300" aria-controls="dropdown-cwspace" data-collapse-toggle="dropdown-cwspace">
                                <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap">CW Space</span>
                                <svg class="sidebar-text w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-cwspace" class="hidden py-2 space-y-2">
                                <li><a href="{{route('cwspace.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('cwspace.*')) active @endif text-gray-900 dark:text-gray-300">CW Space</a></li>
                                <li><a href="{{route('schedule.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('schedule.*')) active @endif text-gray-900 dark:text-gray-300">Schedule</a></li>
                                <li><a href="{{route('reservation.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('reservation.*')) active @endif text-gray-900 dark:text-gray-300">Reservation</a></li>
                            </ul>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 pl-8 text-gray-900 dark:text-gray-300" aria-controls="dropdown-cafe" data-collapse-toggle="dropdown-cafe">
                                <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap">Cafe</span>
                                <svg class="sidebar-text w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-cafe" class="hidden py-2 space-y-2">
                                <li><a href="{{route('beverage.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('beverage.*')) active @endif text-gray-900 dark:text-gray-300">Beverage</a></li>
                                <li><a href="{{route('order.index')}}" class="sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('order.*')) active @endif text-gray-900 dark:text-gray-300">Order</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <button type="button" class="flex items-center w-full p-2 text-base rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-300" aria-controls="dropdown-reports" data-collapse-toggle="dropdown-reports">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                        <span class="sidebar-text flex-1 ms-3 text-left whitespace-nowrap">Reports</span>
                        <svg class="sidebar-text w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-reports" class="hidden py-2 space-y-2">
                        <li><a href="{{route('report.borrowing')}}" class=" sidebar-text flex items-center w-full p-2 rounded-lg pl-14 group hover:bg-gray-100 dark:hover:bg-gray-700 sidebar-link @if(request()->routeIs('borrowing.*')) active @endif text-gray-900 dark:text-gray-300">Borrowing Report</a></li>
                        <li><a href="#" class=" sidebar-text flex items-center w-full p-2 rounded-lg pl-11 group hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-300">x Report</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </aside>

    <main id="main-content" class="p-4 transition-all duration-300 ease-in-out sm:ml-64">
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

            const closeAllDropdowns = () => {
                allDropdowns.forEach(dropdown => {
                    const trigger = document.querySelector(`[data-collapse-toggle="${dropdown.id}"]`);
                    if (trigger && !dropdown.classList.contains('hidden')) {
                        const collapse = new Collapse(dropdown, trigger);
                        collapse.hide();
                    }
                });
            };

            const applyDesktopState = (state) => {
                const isCollapsed = state === 'collapsed';
                sidebar.classList.toggle('w-15', isCollapsed);
                sidebar.classList.toggle('w-64', !isCollapsed);
                mainContent.classList.toggle('sm:ml-20', isCollapsed);
                mainContent.classList.toggle('sm:ml-64', !isCollapsed);
                topNavbar.classList.toggle('sm:left-20', isCollapsed);
                topNavbar.classList.toggle('sm:left-64', !isCollapsed);
                sidebarTexts.forEach(text => text.classList.toggle('hidden', isCollapsed));
                if (isCollapsed) closeAllDropdowns();
                document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
                    btn.disabled = isCollapsed;
                });
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
                } else {
                    applyMobileState(true);
                }
            });

            closeButton.addEventListener('click', () => applyMobileState(false));
            sidebarBackdrop.addEventListener('click', () => applyMobileState(false));

            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (!isDesktop()) applyMobileState(false);
                });
            });

            window.addEventListener('resize', updateSidebarLayout);
            updateSidebarLayout();

            // SWITCH THEME
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            function applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                } else {
                    document.documentElement.classList.remove('dark');
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                }
            }
            const currentTheme = localStorage.getItem('color-theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyTheme(currentTheme);
            themeToggleBtn.addEventListener('click', function() {
                const newTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                localStorage.setItem('color-theme', newTheme);
                applyTheme(newTheme);
            });
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