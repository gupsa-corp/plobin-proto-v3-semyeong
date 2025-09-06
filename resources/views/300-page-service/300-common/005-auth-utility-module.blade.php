{{-- 유틸리티 함수 모듈 --}}
<script>
/**
 * 유틸리티 함수 모듈
 * 에러 처리, 세션 관리 등 유틸리티 기능
 */
class AuthUtilityModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

    /**
     * 에러 메시지 추출
     */
    extractErrorMessage(response) {
        if (response.errors) {
            // Laravel validation errors
            const errorKeys = Object.keys(response.errors);
            if (errorKeys.length > 0) {
                return response.errors[errorKeys[0]][0];
            }
        }
        return response.message || '요청 처리 중 오류가 발생했습니다.';
    }

    /**
     * 401 에러 처리
     */
    handleUnauthorized() {
        console.warn('인증되지 않은 접근으로 인한 자동 로그아웃');
        this.handleSessionExpired('인증이 필요합니다. 로그인 페이지로 이동합니다.');
    }

    /**
     * 세션 만료 처리
     */
    handleSessionExpired(message = '세션이 만료되었습니다. 다시 로그인해주세요.') {
        this.authManager.tokenModule.removeToken();
        if (confirm(message)) {
            window.location.href = '/login';
        } else {
            window.location.href = '/login';
        }
    }
}

window.AuthUtilityModule = AuthUtilityModule;
</script>