<?php

use App\Http\AuthUser\CheckEmail\Controller as CheckEmailController;
use App\Http\AuthUser\SignupPlobin\Controller as SignupController;
use App\Http\AuthUser\LoginPlobin\Controller as LoginController;
use App\Http\AuthUser\LogoutPlobin\Controller as LogoutController;
use App\Http\AuthUser\ForgotPassword\Controller as ForgotPasswordController;
use App\Http\AuthUser\ResetPassword\Controller as ResetPasswordController;
use App\Http\AuthUser\Me\Controller as MeController;
use App\Http\Organization\CreateOrganization\Controller as CreateOrganizationController;
use App\Http\Organization\GetOrganizations\Controller as GetOrganizationsController;
use App\Http\Organization\GetOrganization\Controller as GetOrganizationController;
use App\Http\Organization\UpdateOrganization\Controller as UpdateOrganizationController;
use App\Http\Organization\DeleteOrganization\Controller as DeleteOrganizationController;
use App\Http\Organization\CheckUrlPath\Controller as CheckUrlPathController;
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
    // 공개 API (인증 불필요) - 단순한 Rate Limit만
    Route::post('/check-email', CheckEmailController::class)
        ->middleware('rate.limit:10,1');

    Route::post('/signup', SignupController::class)
        ->middleware('rate.limit:3,5');

    Route::post('/login', LoginController::class)
        ->middleware('rate.limit:5,1');

    Route::post('/forgot-password', ForgotPasswordController::class)
        ->middleware('rate.limit:3,1');

    Route::post('/reset-password', ResetPasswordController::class)
        ->middleware('rate.limit:5,1');

    // 인증 필요한 API (웹 세션 OR API 토큰)
    Route::middleware(['auth.web-or-token', 'rate.limit:60,1'])->group(function () {
        Route::get('/me', MeController::class);
        Route::post('/logout', LogoutController::class);
    });
});

Route::prefix('organizations')->middleware(['auth.web-or-token', 'rate.limit:60,1'])->group(function () {
    Route::get('/list', GetOrganizationsController::class);
    Route::post('/create', CreateOrganizationController::class);
    Route::get('/check-url/{url_path}', CheckUrlPathController::class);
    Route::get('/{organization}', GetOrganizationController::class);
    Route::put('/{organization}', UpdateOrganizationController::class);
    Route::delete('/{organization}', DeleteOrganizationController::class);
});
