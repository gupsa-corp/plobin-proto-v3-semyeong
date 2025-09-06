<?php

use App\Http\AuthUser\CheckEmail\Controller as CheckEmailController;
use App\Http\AuthUser\SignupPlobin\Controller as SignupController;
use App\Http\AuthUser\LoginPlobin\Controller as LoginController;
use App\Http\AuthUser\LogoutPlobin\Controller as LogoutController;
use App\Http\AuthUser\ForgotPassword\Controller as ForgotPasswordController;
use App\Http\AuthUser\ResetPassword\Controller as ResetPasswordController;
use App\Http\AuthUser\Me\Controller as MeController;
use App\Http\AuthUser\ValidatePhone\Controller as ValidatePhoneController;
use App\Http\AuthUser\GetCountries\Controller as GetCountriesController;
use App\Http\AuthUser\FormatPhone\Controller as FormatPhoneController;
use App\Http\AuthUser\GetPhoneInfo\Controller as GetPhoneInfoController;
use App\Http\AuthUser\GetAllCountryCodes\Controller as GetAllCountryCodesController;
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
    // 공개 API (인증 불필요) - 개발용으로 제한 완화
    Route::post('/check-email', CheckEmailController::class);
    Route::post('/validate-phone', ValidatePhoneController::class);
    Route::post('/format-phone', FormatPhoneController::class);
    Route::post('/phone-info', GetPhoneInfoController::class);
    Route::post('/signup', SignupController::class);
    Route::post('/login', LoginController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);
});

// 공개 API
Route::get('/countries', GetCountriesController::class);
Route::get('/country-codes', GetAllCountryCodesController::class);


Route::prefix('auth')->group(function () {
    // 인증 필요한 API (웹 세션 OR API 토큰)
    Route::middleware(['auth.web-or-token'])->group(function () {
        Route::get('/me', MeController::class);
        Route::post('/logout', LogoutController::class);
    });
});

// 사용자 프로필 관리 API
Route::prefix('user')->middleware(['auth.web-or-token'])->group(function () {
    Route::put('/profile', App\Http\Controllers\UserController::class . '@updateProfile');
    Route::put('/password', App\Http\Controllers\UserController::class . '@changePassword');
});

Route::prefix('organizations')->middleware(['auth.web-or-token'])->group(function () {
    Route::get('/list', GetOrganizationsController::class);
    Route::post('/create', CreateOrganizationController::class);
    Route::get('/check-url/{url_path}', CheckUrlPathController::class);
    Route::get('/{organization}', GetOrganizationController::class);
    Route::put('/{organization}', UpdateOrganizationController::class);
    Route::delete('/{organization}', DeleteOrganizationController::class);
});
