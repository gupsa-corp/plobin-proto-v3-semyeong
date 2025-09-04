<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('011-landing.index');
});

Route::get('/signin', function () {
    return view('021-signin.index');
});

Route::get('/signup', function () {
    return view('022-signup.index');
});
