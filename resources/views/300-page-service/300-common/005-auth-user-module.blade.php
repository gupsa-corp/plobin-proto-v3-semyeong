{{-- 사용자 관리 모듈 --}}
<script>
/**
 * 사용자 관리 모듈
 * 사용자 정보 관리 및 인증 상태 확인
 */
class AuthUserModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

    /**
     * 현재 사용자 정보 가져오기
     */
    getUser() {
        return this.authManager.user;
    }

    /**
     * 사용자 정보 설정
     */
    setUser(user) {
        this.authManager.user = user;
        this.authManager.emit('userUpdated', user);
    }

    /**
     * 로그인 여부 확인
     */
    isAuthenticated() {
        return !!(this.authManager.token && this.authManager.user);
    }
}

window.AuthUserModule = AuthUserModule;
</script>