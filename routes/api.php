<?php

use App\Http\Auth\CheckEmail\Controller as CheckEmailController;
use Illuminate\Http\Request;
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
});
