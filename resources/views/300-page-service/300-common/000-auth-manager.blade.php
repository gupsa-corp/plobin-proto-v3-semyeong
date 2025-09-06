{{-- 중앙집중식 인증 관리자 --}}
{{-- 모듈 파일들 로드 --}}
@include('300-page-service.300-common.005-auth-token-module')
@include('300-page-service.300-common.005-auth-user-module')
@include('300-page-service.300-common.005-auth-http-module')
@include('300-page-service.300-common.005-auth-operations-module')
@include('300-page-service.300-common.005-auth-validation-module')
@include('300-page-service.300-common.005-auth-event-module')
@include('300-page-service.300-common.005-auth-utility-module')

<script>
/**
 * 중앙집중식 인증 관리 클래스
 * 모듈화된 인증 관련 기능을 통합 관리
 */
class AuthManager {
    constructor() {
        this.token = null;
        this.user = null;
        this.isInitialized = false;
        this.listeners = {};
        
        // 모듈 초기화
        this.initializeModules();
        
        // AuthManager 초기화
        this.init();
    }

    /**
     * 모듈 초기화
     */
    initializeModules() {
        this.tokenModule = new AuthTokenModule(this);
        this.userModule = new AuthUserModule(this);
        this.httpModule = new AuthHttpModule(this);
        this.operationsModule = new AuthOperationsModule(this);
        this.validationModule = new AuthValidationModule(this);
        this.eventModule = new AuthEventModule(this);
        this.utilityModule = new AuthUtilityModule(this);
    }

    /**
     * 초기화 함수
     */
    init() {
        if (this.isInitialized) return;
        
        this.token = this.tokenModule.getStoredToken();
        this.isInitialized = true;
        
        // 토큰이 있으면 사용자 정보 로드 시도
        if (this.token) {
            this.tokenModule.validateToken();
        }
        
        console.log('AuthManager 초기화 완료');
    }

    // ====================================================================
    // 토큰 관리 위임 메서드
    // ====================================================================
    getStoredToken() { return this.tokenModule.getStoredToken(); }
    setToken(token) { return this.tokenModule.setToken(token); }
    removeToken() { return this.tokenModule.removeToken(); }
    hasToken() { return this.tokenModule.hasToken(); }
    validateToken() { return this.tokenModule.validateToken(); }

    // ====================================================================
    // 사용자 관리 위임 메서드
    // ====================================================================
    getUser() { return this.userModule.getUser(); }
    setUser(user) { return this.userModule.setUser(user); }
    isAuthenticated() { return this.userModule.isAuthenticated(); }

    // ====================================================================
    // HTTP 요청 관리 위임 메서드
    // ====================================================================
    getApiHeaders(customHeaders) { return this.httpModule.getApiHeaders(customHeaders); }
    getCsrfToken() { return this.httpModule.getCsrfToken(); }
    makeRequest(url, options) { return this.httpModule.makeRequest(url, options); }

    // ====================================================================
    // 인증 작업 위임 메서드
    // ====================================================================
    login(email, password, remember) { return this.operationsModule.login(email, password, remember); }
    logout(redirectUrl) { return this.operationsModule.logout(redirectUrl); }
    signup(userData) { return this.operationsModule.signup(userData); }
    forgotPassword(email) { return this.operationsModule.forgotPassword(email); }
    resetPassword(resetData) { return this.operationsModule.resetPassword(resetData); }

    // ====================================================================
    // 유효성 검사 위임 메서드
    // ====================================================================
    validateEmail(email) { return this.validationModule.validateEmail(email); }
    validatePassword(password) { return this.validationModule.validatePassword(password); }
    validateLoginForm(email, password) { return this.validationModule.validateLoginForm(email, password); }
    validateSignupForm(userData) { return this.validationModule.validateSignupForm(userData); }
    validateResetPasswordForm(resetData) { return this.validationModule.validateResetPasswordForm(resetData); }

    // ====================================================================
    // 이벤트 시스템 위임 메서드
    // ====================================================================
    on(event, callback) { return this.eventModule.on(event, callback); }
    off(event, callback) { return this.eventModule.off(event, callback); }
    emit(event, data) { return this.eventModule.emit(event, data); }

    // ====================================================================
    // 유틸리티 함수 위임 메서드
    // ====================================================================
    extractErrorMessage(response) { return this.utilityModule.extractErrorMessage(response); }
    handleUnauthorized() { return this.utilityModule.handleUnauthorized(); }
    handleSessionExpired(message) { return this.utilityModule.handleSessionExpired(message); }
}

// 전역 AuthManager 인스턴스 생성 (DOM 로드 후)
document.addEventListener('DOMContentLoaded', function() {
    if (!window.AuthManager) {
        window.AuthManager = new AuthManager();
        console.log('중앙집중식 AuthManager 로드 완료');
    }
});

// 즉시 사용 가능하도록 하는 래퍼
window.getAuthManager = function() {
    return new Promise((resolve) => {
        if (window.AuthManager) {
            resolve(window.AuthManager);
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                resolve(window.AuthManager);
            });
        }
    });
};
</script>