<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

// Route::get('/menu', function(){
//     return view('user.beveragesMenu');
// });

//SHELF CRUD

//CATEGORY CRUD

//BOOK CRUD
Route::get('/book/index',[BookController::class, 'index'])->name('book.index');
Route::get('/book/form',[BookController::class, 'form'])->name('book.form');
Route::post('/book/insert',[BookController::class, 'insert'])->name('book.insert');
Route::get('/book/detail/{id}',[BookController::class, 'detail'])->name('book.detail');
Route::post('/book/update/',action: [BookController::class, 'update'])->name('book.update');
Route::post('/book/delete/{id}',[BookController::class, 'delete'])->name('book.delete');

//HOME
