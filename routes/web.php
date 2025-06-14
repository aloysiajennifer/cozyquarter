<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CwspaceController;
use App\Models\Cwspace;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BeverageController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CoworkingSpaceController;
use App\Http\Controllers\UserReservationController;

Route::get('/', function () {
    return view('auth.login');
});

// ROUTE AUTHENTICATION LOGIN SIGNUP
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.alert')->group(function () {
    // HOME USER
    Route::get('/home', [BookController::class, 'home'])->name('home');

    // HOME ADMIN
    Route::get('/dashboard', function () {
        return view('admin.layoutAdmin');
    })->name('dashboard');

    //MIDDLEWARE ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        //SHELF CRUD
        Route::get('/shelf/index', [ShelfController::class, 'index'])->name('shelf.index');
        Route::get('/shelf/form', [ShelfController::class, 'form'])->name('shelf.form');
        Route::post('/shelf/insert', [ShelfController::class, 'insert'])->name('shelf.insert');
        Route::get('/shelf/detail/{id}', [ShelfController::class, 'detail'])->name('shelf.detail');
        Route::post('/shelf/update/', action: [ShelfController::class, 'update'])->name('shelf.update');
        Route::post('/shelf/delete/{id}', [ShelfController::class, 'delete'])->name('shelf.delete');

        //CATEGORY CRUD
        Route::get('/category/index', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category/form', [CategoryController::class, 'form'])->name('category.form');
        Route::post('/category/insert', [CategoryController::class, 'insert'])->name('category.insert');
        Route::get('/category/detail/{id}', [CategoryController::class, 'detail'])->name('category.detail');
        Route::post('/category/update/', action: [CategoryController::class, 'update'])->name('category.update');
        Route::post('/category/delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');

        //BOOK CRUD
        Route::get('/book/index', [BookController::class, 'index'])->name('book.index');
        Route::get('/book/form', [BookController::class, 'form'])->name('book.form');
        Route::post('/book/insert', [BookController::class, 'insert'])->name('book.insert');
        Route::get('/book/detail/{id}', [BookController::class, 'detail'])->name('book.detail');
        Route::post('/book/update/', action: [BookController::class, 'update'])->name('book.update');
        Route::post('/book/delete/{id}', [BookController::class, 'delete'])->name('book.delete');

        //BEVERAGES CRUD
        Route::prefix('beverage')->group(function () {
            Route::get('/index', [BeverageController::class, 'index'])->name('beverage.index'); // List beverage
            Route::get('/form', [BeverageController::class, 'create'])->name('beverage.create'); // Form tambah
            Route::post('/store', [BeverageController::class, 'store'])->name('beverage.store'); // Simpan baru
            Route::get('/edit/{id}', [BeverageController::class, 'edit'])->name('beverage.edit'); // Form edit
            Route::put('/update/{id}', [BeverageController::class, 'update'])->name('beverage.update');
            Route::post('/delete/{id}', [BeverageController::class, 'destroy'])->name('beverage.delete'); // Hapus data
        });

        // BORROWING ADMIN
        Route::get('/borrowing/index', [BorrowingController::class, 'index'])->name('borrowing.index');
        Route::put('/borrowing/returned/{id}', [BorrowingController::class, 'returned'])->name('borrowing.returned');
        Route::put('/fine/paid/{id}', [FineController::class, 'paid'])->name('fine.paid');
        Route::get('/borrowing/form', [BorrowingController::class, 'form'])->name('borrowing.form');
        Route::post('/borrowing/insert', [BorrowingController::class, 'insert'])->name('borrowing.insert');

        // CWSPACE CRUD
        Route::get('/cwspace/index', [CwspaceController::class, 'index'])->name('cwspace.index'); //return the page with all cwspaces
        Route::post('/cwspace/insert', [CwspaceController::class, 'insert'])->name('cwspace.insert'); //add cwspace to the db
        Route::put('/cwspace/{id}', [CwspaceController::class, 'update'])->name('cwspace.update'); //update
        Route::delete('/cwspace/{id}', [CwspaceController::class, 'delete'])->name('cwspace.delete');


        // SCHEDULES CRUD
        Route::get('/schedules/index', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::put('/schedules/{id}',  [ScheduleController::class, 'update'])->name('schedule.update');


        //RESERVATION CRUD
        Route::get('/reservation/index', [ReservationController::class, 'index'])->name('reservation.index');
        Route::put('/reservation/{reservation}/update-status', [ReservationController::class, 'updateStatus'])->name('reservation.updateStatus');

        //ORDER CRUD
        Route::get('/order/index', [OrderController::class, 'index'])->name('order.index');
        Route::put('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');

        // REPORTS
        Route::get('/report/borrowing', [ReportController::class, 'borrowing'])->name('report.borrowing');
        Route::get('/report/borrowing/pdf', [ReportController::class, 'borrowingPDF'])->name('report.borrowingPDF');
    });

    //MIDDLEWARE USER
    Route::middleware('role:user')->group(function () {

        // BOOKS BORROWED LIBRARY (USER)
        Route::get('/library/booksborrowed', [BorrowingController::class, 'borrowed'])->name('library.borrowed');

        //BEVERAGES USER
        Route::get('/menu', [BeverageController::class, 'menu'])->name('beverages.menu');

        //ORDER USER
        Route::get('/yourOrder', [OrderController::class, 'yourOrder'])->name('yourOrder');
        Route::post('/placeOrder', [OrderController::class, 'placeOrder'])->name('placeOrder');
        
        // CO-WORKING SPACE ROUTES (User-facing)
        // Now protected by 'auth' middleware
        Route::get('/coworking/schedule', [CoworkingSpaceController::class, 'showSchedule'])->name('coworking.schedule');
        Route::post('/coworking/book', [CoworkingSpaceController::class, 'storeReservation'])->name('coworking.book');

        // NEW: USER RESERVATION ROUTES
        Route::prefix('user/reservations')->name('user.reservations.')->group(function () {
            Route::get('/', [UserReservationController::class, 'index'])->name('index');
            Route::post('/{id}/cancel', [UserReservationController::class, 'cancelReservation'])->name('cancel');
            Route::post('/{id}/attend', [UserReservationController::class, 'markAttended'])->name('attend'); // For 'Mark Attended' button
            Route::get('/{id}/order-drink', [UserReservationController::class, 'orderDrink'])->name('order-drink'); // Redirect to beverage menu
        });
    });
});

//HOME LIBRARY USER
Route::get('/library/home', [BookController::class, 'home'])->name('library.home');
