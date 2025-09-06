{{-- 토큰 관리 모듈 --}}
<script>
/**
 * 토큰 관리 모듈
 * 토큰 저장, 검증, 제거 등 토큰 관련 기능
 */
class AuthTokenModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

    /**
     * 저장된 토큰 가져오기
     */
    getStoredToken() {
        return localStorage.getItem('auth_token');
    }

    /**
     * 토큰 저장
     */
    setToken(token) {
        if (!token) return;

        this.authManager.token = token;
        localStorage.setItem('auth_token', token);
        this.authManager.emit('tokenSet', token);
    }

    /**
     * 토큰 제거
     */
    removeToken() {
        this.authManager.token = null;
        this.authManager.user = null;
        localStorage.removeItem('auth_token');
        this.authManager.emit('tokenRemoved');
    }

    /**
     * 토큰 존재 여부 확인
     */
    hasToken() {
        return !!this.authManager.token;
    }

    /**
     * 토큰 유효성 검사
     */
    async validateToken() {
        if (!this.authManager.token) return false;

        try {
            const response = await this.authManager.httpModule.makeRequest('/api/auth/me', {
                method: 'GET'
            });

            if (response.success && response.data) {
                this.authManager.userModule.setUser(response.data);
                this.authManager.emit('userLoaded', response.data);
                return true;
            } else {
                this.removeToken();
                return false;
            }
        } catch (error) {
            console.warn('토큰 검증 실패:', error);
            this.removeToken();
            return false;
        }
    }
}

window.AuthTokenModule = AuthTokenModule;
</script>
