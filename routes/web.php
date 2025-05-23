<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

// Route::get('/menu', function(){
//     return view('user.beveragesMenu');
// });

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

//HOME

// BORROWING
Route::get('/borrowing/index', [BorrowingController::class, 'index'])->name('borrowing.index');
Route::get('/borrowing/form',[BorrowingController::class, 'form'])->name('borrowing.form');
Route::post('/borrowing/insert',[BorrowingController::class, 'insert'])->name('borrowing.insert');
