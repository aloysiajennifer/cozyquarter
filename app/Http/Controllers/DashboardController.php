<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing; // Pastikan model ini ada
use App\Models\Cwspace; // Perhatikan case sensitive: Cwspace bukan CwSpace
use App\Models\Reservation;
use App\Models\Beverage;
use App\Models\Beverages;
use App\Models\Order;
// use App\Models\OrderItem; // Uncomment jika Anda membuat model OrderItem dan menggunakannya
use Carbon\Carbon; // Untuk bekerja dengan tanggal dan waktu
use Illuminate\Support\Facades\DB; // Untuk agregasi jika diperlukan

class DashboardController extends Controller
{
    public function index()
    {
        // Library Statistics
        $totalBooks = Book::count();
        $availableBooks = Book::where('status_book', 1)->count();
        $totalBorrowingRecords = Borrowing::count();


        // Co-working Space Statistics
        $totalCwSpaces = Cwspace::count();
        $openCwSpaces = Cwspace::where('status_cwspace', 1)->count();

        $activeReservations = Reservation::whereDate('reservation_date', '>=', Carbon::today())
            ->whereIn('status_reservation', [0, 2]) // 0=reserved, 2=attended
            ->count();

        // Cafe & Beverage Statistics
        $totalBeverages = Beverages::count();
        // OrdersToday: Pesanan yang sudah dibayar (status_order = 1) dan dibuat hari ini
        $ordersToday = Order::whereDate('created_at', Carbon::today())
            ->where('status_order', 1) // 1: paid
            ->count();
        // TodaysRevenue:Total harga dari pesanan yang sudah dibayar hari ini
        $todaysRevenue = Order::whereDate('created_at', Carbon::today())
            ->where('status_order', 1)
            ->sum('total_price');

        // Ambil 3 yg plg dkt
        $reservations = Reservation::with(['user', 'schedule.cwspace'])
            ->whereDate('reservation_date', '>=', Carbon::today())
            ->whereIn('status_reservation', [0, 1])
            ->orderBy('reservation_date')
            ->orderBy('reservation_start_time')
            ->limit(3)
            ->get();


        return view('admin.dashboard.index', compact( 
            'totalBooks',
            'availableBooks',
            'totalBorrowingRecords',
            'totalCwSpaces',
            'openCwSpaces',
            'activeReservations',
            'totalBeverages',
            'ordersToday',
            'todaysRevenue',
            'reservations',
        ));
    }
}
