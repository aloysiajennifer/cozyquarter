<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CwspaceController;
use App\Models\Cwspace;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BeverageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

// Route::get('/menu', function(){
//     return view('user.beveragesMenu');
// });

//ROUTE NAVBAR

//SHELF CRUD
Route::get('/shelf/index',[ShelfController::class, 'index'])->name('shelf.index');
Route::get('/shelf/form',[ShelfController::class, 'form'])->name('shelf.form');
Route::post('/shelf/insert',[ShelfController::class, 'insert'])->name('shelf.insert');
Route::get('/shelf/detail/{id}',[ShelfController::class, 'detail'])->name('shelf.detail');
Route::post('/shelf/update/',action: [ShelfController::class, 'update'])->name('shelf.update');
Route::post('/shelf/delete/{id}',[ShelfController::class, 'delete'])->name('shelf.delete');

//CATEGORY CRUD
Route::get('/category/index',[CategoryController::class, 'index'])->name('category.index');
Route::get('/category/form',[CategoryController::class, 'form'])->name('category.form');
Route::post('/category/insert',[CategoryController::class, 'insert'])->name('category.insert');
Route::get('/category/detail/{id}',[CategoryController::class, 'detail'])->name('category.detail');
Route::post('/category/update/',action: [CategoryController::class, 'update'])->name('category.update');
Route::post('/category/delete/{id}',[CategoryController::class, 'delete'])->name('category.delete');

//BOOK CRUD
Route::get('/book/index',[BookController::class, 'index'])->name('book.index');
Route::get('/book/form',[BookController::class, 'form'])->name('book.form');
Route::post('/book/insert',[BookController::class, 'insert'])->name('book.insert');
Route::get('/book/detail/{id}',[BookController::class, 'detail'])->name('book.detail');
Route::post('/book/update/',action: [BookController::class, 'update'])->name('book.update');
Route::post('/book/delete/{id}',[BookController::class, 'delete'])->name('book.delete');

//HOME LIBRARY
Route::get('/library/home',[BookController::class, 'home'])->name('library.home');

// BORROWING
Route::get('/borrowing/index', [BorrowingController::class, 'index'])->name('borrowing.index');
Route::get('/borrowing/form',[BorrowingController::class, 'form'])->name('borrowing.form');
Route::post('/borrowing/insert',[BorrowingController::class, 'insert'])->name('borrowing.insert');

// HOME ADMIN
Route::get('admin/home', function(){
    return view('admin.layoutAdmin');
});

// CWSPACE CRUD
Route::get('/cwspace/index', [CwspaceController::class, 'index'])->name('cwspace.index'); //return the page with all cwspaces
Route::post('/cwspace/insert',[CwspaceController::class, 'insert'])->name('cwspace.insert'); //add cwspace to the db
Route::put('/cwspace/{id}', [CwspaceController::class, 'update'])->name('cwspace.update'); //update
Route::delete('/cwspace/{id}', [CwspaceController::class, 'delete'])->name('cwspace.delete');

//BEVERAGES CRUD
Route::prefix('beverage')->group(function () {
    Route::get('/index', [BeverageController::class, 'index'])->name('beverage.index'); // List beverage
    Route::get('/form', [BeverageController::class, 'create'])->name('beverage.create'); // Form tambah
   
    Route::post('/store', [BeverageController::class, 'store'])->name('beverage.store'); // Simpan baru
    Route::get('/edit/{id}', [BeverageController::class, 'edit'])->name('beverage.edit'); // Form edit
    Route::put('/update/{id}', [BeverageController::class, 'update'])->name('beverage.update');
    Route::post('/delete/{id}', [BeverageController::class, 'destroy'])->name('beverage.delete'); // Hapus data
    
});