<?php

use App\Http\AuthUser\CheckEmail\Controller as CheckEmailController;
use App\Http\AuthUser\SignupPlobin\Controller as SignupController;
use App\Http\AuthUser\LoginPlobin\Controller as LoginController;
use App\Http\AuthUser\LogoutPlobin\Controller as LogoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - 단순하게 정리됨
|--------------------------------------------------------------------------
|
| 두 가지 인증만 지원:
| 1. 웹 세션 인증 (브라우저)
| 2. API 토큰 인증 (Bearer token)
|
*/

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
