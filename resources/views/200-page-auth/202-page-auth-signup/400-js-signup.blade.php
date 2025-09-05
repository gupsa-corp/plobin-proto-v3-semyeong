{{-- 회원가입 페이지 JavaScript 로직 (분리된 파일들 포함) --}}
@include('200-page-auth.202-page-auth-signup.400-js-global-state')
@include('200-page-auth.202-page-auth-signup.401-js-country-loader')
@include('200-page-auth.202-page-auth-signup.402-js-name-validation')
@include('200-page-auth.202-page-auth-signup.403-js-nickname-validation')
@include('200-page-auth.202-page-auth-signup.404-js-phone-validation')
@include('200-page-auth.202-page-auth-signup.405-js-password-validation')
@include('200-page-auth.202-page-auth-signup.406-js-email-handler')
@include('200-page-auth.202-page-auth-signup.407-js-form-validation')
@include('200-page-auth.202-page-auth-signup.408-js-form-submit')

{{-- 실시간 유효성 검사 통합 설정 --}}
<script>
// 실시간 유효성 검사 통합 함수
function setupRealTimeValidation() {
    setupFirstNameValidation();
    setupLastNameValidation();
    setupNicknameValidation();
    setupPhoneValidation();
    setupPasswordValidation();
    setupPasswordConfirmationValidation();
}
</script>

{{-- 초기화 및 이벤트 설정 --}}
<script>
// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    setupRealTimeValidation();
    setupEmailInputHandler();
    setupEmailCheckButton();
    setupFormSubmit();
    loadCountries();
});
</script>