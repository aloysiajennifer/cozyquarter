@extends('admin.layoutAdmin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="p-4 sm:p-6 pt-20 transition-all duration-300 ease-in-out sm:ml-64 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto py-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Overview Dashboard</h1>

        <h2 class="text-2xl font-semibold text-gray-300 mb-4">Library Statistics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Total Books</h3>
                    <p class="text-4xl font-bold text-white mt-2">5,200</p>
                </div>
                <div class="p-3 bg-blue-600 rounded-full">
                    <i class="fas fa-book text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Available Books</h3>
                    <p class="text-4xl font-bold text-white mt-2">3,150</p>
                </div>
                <div class="p-3 bg-green-600 rounded-full">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Active Borrowings</h3>
                    <p class="text-4xl font-bold text-white mt-2">789</p>
                </div>
                <div class="p-3 bg-yellow-500 rounded-full">
                    <i class="fas fa-exchange-alt text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Total Users</h3>
                    <p class="text-4xl font-bold text-white mt-2">1,234</p>
                </div>
                <div class="p-3 bg-purple-600 rounded-full">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-300 mb-4 mt-8">Co-working Space Statistics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Total CW Spaces</h3>
                    <p class="text-4xl font-bold text-white mt-2">15</p>
                </div>
                <div class="p-3 bg-indigo-600 rounded-full">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Schedules Today</h3>
                    <p class="text-4xl font-bold text-white mt-2">45</p>
                </div>
                <div class="p-3 bg-teal-600 rounded-full">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Active Reservations</h3>
                    <p class="text-4xl font-bold text-white mt-2">28</p>
                </div>
                <div class="p-3 bg-pink-600 rounded-full">
                    <i class="fas fa-clipboard-list text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Penalties (Mo.)</h3>
                    <p class="text-4xl font-bold text-white mt-2">12</p>
                </div>
                <div class="p-3 bg-red-600 rounded-full">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-300 mb-4 mt-8">Cafe & Beverage Statistics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Total Beverages</h3>
                    <p class="text-4xl font-bold text-white mt-2">45</p>
                </div>
                <div class="p-3 bg-orange-600 rounded-full">
                    <i class="fas fa-mug-hot text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Orders Today</h3>
                    <p class="text-4xl font-bold text-white mt-2">87</p>
                </div>
                <div class="p-3 bg-lime-600 rounded-full">
                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Today's Revenue</h3>
                    <p class="text-4xl font-bold text-white mt-2">Rp 1.250.000</p>
                </div>
                <div class="p-3 bg-cyan-600 rounded-full">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 flex items-center justify-between transform transition duration-300 hover:scale-105">
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">Best Seller (Today)</h3>
                    <p class="text-3xl font-bold text-white mt-2">Cappuccino</p>
                </div>
                <div class="p-3 bg-brown-600 rounded-full" style="background-color: #A0522D;"> <i class="fas fa-coffee text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-gray-800 rounded-lg shadow-lg p-6 animate-fade-in">
                <h3 class="text-xl font-semibold text-gray-300 mb-4">Overall Usage Trends</h3>
                <div class="w-full h-64 bg-gray-700 rounded-md flex items-center justify-center text-gray-400 text-sm">
                    <p>Combined Chart Placeholder (e.g., Line Chart for Borrowings, Reservations, Orders)</p>
                </div>
                <p class="text-gray-400 text-sm mt-3">Visualisasi gabungan tren dari peminjaman buku, reservasi CW Space, dan jumlah pesanan cafe.</p>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 animate-fade-in-delay-100">
                <h3 class="text-xl font-semibold text-gray-300 mb-4">Recent Activity Feed</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-clipboard-check text-green-500 mr-2"></i> Reservation confirmed for CW-05 (User A)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-undo text-blue-500 mr-2"></i> Book "The Hobbit" returned (User B)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-coffee text-yellow-500 mr-2"></i> New order for Latte (Table 3)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-book-reader text-purple-500 mr-2"></i> New user registered: Clara Oswald
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-times-circle text-red-500 mr-2"></i> Schedule 10:00-11:00 closed (CW-02)
                    </li>
                </ul>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 animate-fade-in-delay-200">
                <h3 class="text-xl font-semibold text-gray-300 mb-4">Upcoming CW Space Reservations</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Space</th>
                                <th scope="col" class="px-4 py-3">Time</th>
                                <th scope="col" class="px-4 py-3">User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                                <td class="px-4 py-3">CW-01</td>
                                <td class="px-4 py-3">09:00 - 10:00</td>
                                <td class="px-4 py-3">User X</td>
                            </tr>
                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                                <td class="px-4 py-3">CW-03</td>
                                <td class="px-4 py-3">10:00 - 11:00</td>
                                <td class="px-4 py-3">User Y</td>
                            </tr>
                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                                <td class="px-4 py-3">CW-05</td>
                                <td class="px-4 py-3">13:00 - 14:00</td>
                                <td class="px-4 py-3">User Z</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6 animate-fade-in-delay-300">
                <h3 class="text-xl font-semibold text-gray-300 mb-4">Top 5 Selling Beverages</h3>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-chart-line text-green-400 mr-2"></i> Cappuccino (120 orders)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-chart-line text-green-400 mr-2"></i> Iced Tea (95 orders)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-chart-line text-green-400 mr-2"></i> Espresso (80 orders)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-chart-line text-green-400 mr-2"></i> Green Tea (70 orders)
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-chart-line text-green-400 mr-2"></i> Mineral Water (60 orders)
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0; /* Pastikan awalnya tersembunyi */
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
</style>

@endsection