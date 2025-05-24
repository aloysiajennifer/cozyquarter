<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CwspaceController;
use App\Models\Cwspace;
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

// CWSPACE CRUD
Route::get('/cwspace/index', [CwspaceController::class, 'index'])->name('cwspace.index'); //return the page with all cwspaces
Route::post('/cwspace/insert',[CwspaceController::class, 'insert'])->name('cwspace.insert'); //add cwspace to the db
Route::put('/cwspace/{id}', [CwspaceController::class, 'update'])->name('cwspace.update'); //update
Route::delete('/cwspace/{id}', [CwspaceController::class, 'delete'])->name('cwspace.delete');