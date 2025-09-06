{{-- 이벤트 시스템 모듈 --}}
<script>
/**
 * 이벤트 시스템 모듈
 * 이벤트 리스너 관리 및 이벤트 발생 처리
 */
class AuthEventModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

    /**
     * 이벤트 리스너 추가
     */
    on(event, callback) {
        if (!this.authManager.listeners[event]) {
            this.authManager.listeners[event] = [];
        }
        this.authManager.listeners[event].push(callback);
    }

    /**
     * 이벤트 리스너 제거
     */
    off(event, callback) {
        if (this.authManager.listeners[event]) {
            this.authManager.listeners[event] = this.authManager.listeners[event].filter(cb => cb !== callback);
        }
    }

    /**
     * 이벤트 발생
     */
    emit(event, data) {
        if (this.authManager.listeners[event]) {
            this.authManager.listeners[event].forEach(callback => callback(data));
        }
    }
}

window.AuthEventModule = AuthEventModule;
</script>