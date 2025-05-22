<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

// Route::get('/menu', function(){
//     return view('user.beveragesMenu');
// });