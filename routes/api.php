<?php

use App\Http\AuthUser\CheckEmail\Controller as CheckEmailController;
use App\Http\AuthUser\SignupPlobin\Controller as SignupController;
use App\Http\AuthUser\LoginPlobin\Controller as LoginController;
use App\Http\AuthUser\LogoutPlobin\Controller as LogoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 200Auth 도메인 - 인증 관련 API
Route::prefix('auth')->group(function () {
    // 001RegisterPlobin: 플로빈 회원가입
    Route::post('/check-email', CheckEmailController::class);
    Route::post('/signup', SignupController::class);
    Route::post('/login', LoginController::class);

    // 인증 필요한 라우트
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', LogoutController::class);
    });
});
