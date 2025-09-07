<?php

use App\Http\CoreApi\User\CheckEmail\Controller as CheckEmailController;
use App\Http\CoreApi\User\SignupPlobin\Controller as SignupController;
use App\Http\CoreApi\User\LoginPlobin\Controller as LoginController;
use App\Http\CoreApi\User\LogoutPlobin\Controller as LogoutController;
use App\Http\CoreApi\User\ForgotPassword\Controller as ForgotPasswordController;
use App\Http\CoreApi\User\ResetPassword\Controller as ResetPasswordController;
use App\Http\CoreApi\User\Me\Controller as MeController;
use App\Http\CoreApi\User\GetProfile\Controller as GetProfileController;
use App\Http\CoreApi\User\VerifyPassword\Controller as VerifyPasswordController;
use App\Http\CoreApi\User\UpdateProfile\Controller as UpdateProfileController;
use App\Http\CoreApi\User\ChangePassword\Controller as ChangePasswordController;
use App\Http\CoreApi\User\ValidatePhone\Controller as ValidatePhoneController;
use App\Http\CoreApi\User\GetCountries\Controller as GetCountriesController;
use App\Http\CoreApi\User\FormatPhone\Controller as FormatPhoneController;
use App\Http\CoreApi\User\GetPhoneInfo\Controller as GetPhoneInfoController;
use App\Http\CoreApi\User\GetAllCountryCodes\Controller as GetAllCountryCodesController;
use App\Http\CoreApi\Organization\CreateOrganization\Controller as CreateOrganizationController;
use App\Http\CoreApi\Organization\GetOrganizations\Controller as GetOrganizationsController;
use App\Http\CoreApi\Organization\GetOrganization\Controller as GetOrganizationController;
use App\Http\CoreApi\Organization\UpdateOrganization\Controller as UpdateOrganizationController;
use App\Http\CoreApi\Organization\DeleteOrganization\Controller as DeleteOrganizationController;
use App\Http\CoreApi\Organization\CheckUrlPath\Controller as CheckUrlPathController;
use App\Http\CoreApi\ProjectPage\Index\Controller as ProjectPageIndexController;
use App\Http\CoreApi\ProjectPage\Store\Controller as ProjectPageStoreController;
use App\Http\CoreApi\ProjectPage\Show\Controller as ProjectPageShowController;
use App\Http\CoreApi\ProjectPage\Update\Controller as ProjectPageUpdateController;
use App\Http\CoreApi\ProjectPage\Destroy\Controller as ProjectPageDestroyController;
use App\Http\CoreApi\ProjectPage\UpdateOrder\Controller as ProjectPageUpdateOrderController;
use App\Http\CoreApi\Organization\SearchMembers\Controller as SearchMembersController;
use App\Http\CoreApi\Organization\InviteMembers\Controller as InviteMembersController;
use App\Http\CoreApi\OrganizationBilling\GetBillingData\Controller as GetBillingDataController;
use App\Http\CoreApi\OrganizationBilling\ProcessPayment\Controller as ProcessPaymentController;
use App\Http\CoreApi\OrganizationBilling\CreateBusinessInfo\Controller as CreateBusinessInfoController;
use App\Http\CoreApi\OrganizationBilling\BusinessLookup\Controller as BusinessLookupController;
use App\Http\CoreApi\OrganizationBilling\DownloadReceipt\Controller as DownloadReceiptController;
use App\Http\CoreApi\User\SearchUsers\Controller as SearchUsersController;
use App\Http\CoreApi\Sandbox\FileList\Controller as SandboxFileListController;
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

// 사용자 검색 API (플랫폼 관리자용 - 개발용으로 인증 제거)
Route::get('/users/search', SearchUsersController::class);

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

// 프로젝트 페이지 관리 API (개발용 - 인증 제거)
Route::prefix('projects')->group(function () {
    Route::get('/{project}/pages', ProjectPageIndexController::class);
    Route::post('/{project}/pages', ProjectPageStoreController::class);
    Route::get('/{project}/pages/{page}', ProjectPageShowController::class);
    Route::put('/{project}/pages/{page}', ProjectPageUpdateController::class);
    Route::delete('/{project}/pages/{page}', ProjectPageDestroyController::class);
});



// 테스트용 결제 API (인증 없음 - 개발용)
Route::prefix('test/organizations')->group(function () {
    Route::get('{organization}/billing/data', GetBillingDataController::class);
    Route::post('{organization}/billing/business-info', CreateBusinessInfoController::class);
    Route::post('{organization}/billing/business-lookup', BusinessLookupController::class);
    Route::post('{organization}/billing/receipt/download', DownloadReceiptController::class);
});

// 샌드박스 API (개발용 - 인증 없음)
Route::prefix('sandbox')->group(function () {
    Route::get('/files', [SandboxFileListController::class, 'getFileList']);
});
