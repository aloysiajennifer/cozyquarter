@extends('admin.layoutAdmin')

@section('title', 'Dashboard Admin')

@php
use Carbon\Carbon;
@endphp

@section('content')
<div class="p-4 sm:p-6 mt-20 transition-all duration-300 ease-in-out min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-center text-3xl font-bold text-[var(--primary)] dark:text-[var(--secondary)] mb-6">Overview Dashboard</h1>

        <h2 class="text-2xl font-semibold text-[var(--accent-blue)] dark:text-[var(--accent-green)] mb-4">Library Statistics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Books -->
            <a href="{{ route('book.index') }}"
                class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 xl:order-1 flex-grow min-w-0 text-center xl:text-left mt-2 xl:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Total Books</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $totalBooks }}</p>
                </div>
                <div class="order-1 xl:order-2 p-2 sm:p-3 xl:p-4 w-10 h-10 sm:w-12 sm:h-12 xl:w-14 xl:h-14 bg-[var(--medium-blue)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-book text-xl sm:text-2xl xl:text-3xl text-white"></i>
                </div>
            </a>

            <!-- available books -->
            <a href="{{ route('book.index', ['status_book' => 1]) }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Available Books</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $availableBooks }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--accent-green)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-xl sm:text-2xl text-white"></i>
                </div>
            </a>

            <!-- ttl borrowing records -->
            <a href="{{ route('borrowing.index') }}"
                class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 xl:order-1 flex-grow min-w-0 text-center xl:text-left mt-2 xl:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Total Borrowing Records</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $totalBorrowingRecords }}</p>
                </div>
                <div class="order-1 xl:order-2 p-2 sm:p-3 xl:p-4 w-10 h-10 sm:w-12 sm:h-12 xl:w-14 xl:h-14 bg-[var(--highlight)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exchange-alt text-xl sm:text-2xl xl:text-3xl text-white"></i>
                </div>
            </a>
        </div> 

        <h2 class="text-2xl font-semibold text-[var(--accent-blue)] dark:text-[var(--accent-green)] mb-4 mt-8">Co-working Space Statistics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- ttl cwspace -->
            <a href="{{ route('cwspace.index') }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Total CW Spaces</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $totalCwSpaces }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--medium-blue)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-building text-xl sm:text-2xl text-white"></i>
                </div>
            </a>

           <!-- open cwspace -->
            <a href="{{ route('cwspace.index', ['status_cwspace' => 1]) }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Open CW Spaces</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $openCwSpaces }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--accent-green)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-door-open text-xl sm:text-2xl text-white"></i>
                </div>
            </a>

            <!-- active reservations -->
            <a href="{{ route('reservation.index', ['status_reservation' => [0, 2], 'upcoming_or_active' => true]) }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Active Reservations</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $activeReservations }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--highlight)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clipboard-list text-xl sm:text-2xl text-white"></i>
                </div>
            </a>
        </div>

        <h2 class="text-2xl font-semibold text-[var(--accent-blue)] dark:text-[var(--accent-green)] mb-4 mt-8">Beverage Statistics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- ttl bev -->
            <a href="{{ route('beverage.index') }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Total Beverages</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $totalBeverages }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--medium-blue)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-mug-hot text-xl sm:text-2xl text-white"></i>
                </div>
            </a>

            <!-- orders today -->
            <a href="{{ route('order.index', ['date' => Carbon::today()->toDateString()]) }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Orders Today</h3>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mt-1 sm:mt-2">{{ $ordersToday }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--accent-green)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shopping-cart text-xl sm:text-2xl text-white"></i>
                </div>
            </a>

            <!-- today rev -->
            <a href="{{ route('report.orderDrink') }}" class="block bg-gray-800 rounded-lg shadow-lg p-3 sm:p-4 md:p-6 flex flex-col xl:flex-row items-center xl:justify-between
transform transition duration-300 hover:scale-105 shimmer-card">
                <div class="order-2 md:order-1 flex-grow min-w-0 text-center md:text-left mt-2 md:mt-0">
                    <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-300">Today's Revenue</h3>
                    <p class="text-2xl sm:text-2xl md:text-xl font-bold text-white mt-1 sm:mt-2">Rp {{ number_format($todaysRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="order-1 md:order-2 p-2 sm:p-3 w-10 h-10 sm:w-12 sm:h-12 bg-[var(--highlight)] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-xl sm:text-2xl text-white"></i>
                </div>
            </a>
        </div>



        <div class="mt -8 bg-[var(--medium-blue)] rounded-lg shadow-lg p-6 animate-fade-in-delay-200">
            <h3 class="text-xl font-semibold text-white mb-4">Top 3 Upcoming CW Space Reservations</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-white">
                    <thead class="text-xs uppercase bg-[var(--accent-blue)] text-white">
                        <tr>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">CW Space</th>
                            <th scope="col" class="px-4 py-3">Datetime</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                        <tr class="hover:text-[var(--primary)] border-b border-[var(--accent-blue)] hover:bg-[var(--secondary)] transition duration-150">
                            <td class="px-6 py-4 font-medium">{{ $reservation->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $reservation->reservation_code_cwspace ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ Carbon::parse($reservation->reservation_date)->translatedFormat('d F Y') }}</div>
                                <div class="text-xs ">{{ Carbon::parse($reservation->reservation_start_time)->format('H:i') }} - {{ Carbon::parse($reservation->reservation_end_time)->format('H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-white hover:text-[var(--primary)]">No upcoming reservations.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-delay-100 {
        animation: fadeIn 0.6s ease-out 0.1s forwards;
        opacity: 0;
    }

    .animate-fade-in-delay-200 {
        animation: fadeIn 0.6s ease-out 0.2s forwards;
        opacity: 0;
    }

    .animate-fade-in-delay-300 {
        animation: fadeIn 0.6s ease-out 0.3s forwards;
        opacity: 0;
    }

    .shimmer-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(to top, var(--medium-blue), var(--accent-blue), var(--accent-green));
        z-index: 10;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transform: translateZ(0);

    }

    .shimmer-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(to right,
                transparent 0%,
                rgba(var(--accent-blue-rgb), 0.2) 25%,
                rgba(var(--secondary-rgb), 0.6) 50%,
                rgba(var(--accent-green-rgb), 0.2) 75%,
                transparent 100%);
        transform: skewX(-20deg);
        transition: left 0.7s ease-in-out;
        z-index: 2;
        pointer-events: none;
    }

    .shimmer-card:hover::after {
        left: 150%;
    }


    .shimmer-card .bg-\\[var\\(--medium-blue\)\\] {
            background-color: var(--medium-blue) !important;
        }

        .shimmer-card .bg-\\[var\\(--accent-green\)\\] {
                background-color: var(--accent-green) !important;
            }

            .shimmer-card .bg-\\[var\\(--highlight\)\\] {
                    background-color: var(--highlight) !important;
                }
</style>

@endsection