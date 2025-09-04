<?php

use Illuminate\Support\Facades\Route;

// 101 ~ 199 랜딩 페이지
Route::get('/', function () {
    return view('101-landing-home.index');
});

// 201 ~ 299 인증 페이지
Route::get('/login', function () {
    return view('201-auth-login.index');
});
Route::get('/signup', function () {
    return view('202-auth-signup.index');
});

// 301 ~ 399 서비스 페이지
Route::get('/dashboard', function () {
    return view('301-service-dashboard.index');
});

// 900 ~ 999 관리자 페이지
Route::get('/admin', function () {
    return view('901-admin-dashboard.index');
});

Route::get('/admin/users', function () {
    return view('902-admin-users.index');
});

Route::get('/admin/settings', function () {
    return view('903-admin-settings.index');
});
