<?php

use App\Http\AuthUser\CheckEmail\Controller as CheckEmailController;
use App\Http\AuthUser\SignupPlobin\Controller as SignupController;
use App\Http\AuthUser\LoginPlobin\Controller as LoginController;
use App\Http\AuthUser\LogoutPlobin\Controller as LogoutController;
use App\Http\AuthUser\ForgotPassword\Controller as ForgotPasswordController;
use App\Http\AuthUser\ResetPassword\Controller as ResetPasswordController;
use App\Http\AuthUser\Me\Controller as MeController;
use App\Http\AuthUser\GetProfile\Controller as GetProfileController;
use App\Http\AuthUser\VerifyPassword\Controller as VerifyPasswordController;
use App\Http\AuthUser\UpdateProfile\Controller as UpdateProfileController;
use App\Http\AuthUser\ChangePassword\Controller as ChangePasswordController;
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
use App\Http\ProjectPage\CreatePage\Controller as CreatePageController;
use App\Http\ProjectPage\GetPages\Controller as GetPagesController;
use App\Http\ProjectPage\GetPage\Controller as GetPageController;
use App\Http\ProjectPage\UpdatePage\Controller as UpdatePageController;
use App\Http\ProjectPage\DeletePage\Controller as DeletePageController;
use App\Http\Organization\SearchMembers\Controller as SearchMembersController;
use App\Http\Organization\InviteMembers\Controller as InviteMembersController;
use App\Http\OrganizationBilling\GetBillingData\Controller as GetBillingDataController;
use App\Http\OrganizationBilling\ProcessPayment\Controller as ProcessPaymentController;
use App\Http\OrganizationBilling\CreateBusinessInfo\Controller as CreateBusinessInfoController;
use App\Http\OrganizationBilling\BusinessLookup\Controller as BusinessLookupController;
use App\Http\OrganizationBilling\DownloadReceipt\Controller as DownloadReceiptController;
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
    Route::get('/profile', GetProfileController::class);
    Route::post('/verify-password', VerifyPasswordController::class);
    Route::put('/profile', UpdateProfileController::class);
    Route::put('/password', ChangePasswordController::class);
});

Route::prefix('organizations')->middleware(['auth.web-or-token'])->group(function () {
    Route::get('/list', GetOrganizationsController::class);
    Route::post('/create', CreateOrganizationController::class);
    Route::get('/check-url/{url_path}', CheckUrlPathController::class);
    Route::get('/{organization}', GetOrganizationController::class);
    Route::put('/{organization}', UpdateOrganizationController::class);
    Route::delete('/{organization}', DeleteOrganizationController::class);

    // 조직 멤버 관리 API
    Route::prefix('{organization}/members')->group(function () {
        Route::get('/search', SearchMembersController::class);
        Route::post('/invite', InviteMembersController::class);
    });

    // 조직 결제 관리 API
    Route::prefix('{organization}/billing')->group(function () {
        Route::get('/data', GetBillingDataController::class);
        Route::post('/payment/confirm', ProcessPaymentController::class);
        Route::post('/business-info', CreateBusinessInfoController::class);
        Route::post('/business-lookup', BusinessLookupController::class);
        Route::post('/receipt/download', DownloadReceiptController::class);
    });
});

// 프로젝트 페이지 관리 API
Route::prefix('projects')->middleware(['auth.web-or-token'])->group(function () {
    Route::get('/{project}/pages', GetPagesController::class);
    Route::post('/{project}/pages', CreatePageController::class);
    Route::get('/{project}/pages/{page}', GetPageController::class);
    Route::put('/{project}/pages/{page}', UpdatePageController::class);
    Route::delete('/{project}/pages/{page}', DeletePageController::class);
});

// 테스트용 결제 API (인증 없음 - 개발용)
Route::prefix('test/organizations')->group(function () {
    Route::get('{organization}/billing/data', GetBillingDataController::class);
    Route::post('{organization}/billing/business-info', CreateBusinessInfoController::class);
    Route::post('{organization}/billing/business-lookup', BusinessLookupController::class);
    Route::post('{organization}/billing/receipt/download', DownloadReceiptController::class);
});
