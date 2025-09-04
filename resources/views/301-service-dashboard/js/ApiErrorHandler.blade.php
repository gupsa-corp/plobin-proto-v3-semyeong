{{-- API 오류 처리 클래스 --}}
<script>
/**
 * API 오류 처리를 담당하는 클래스
 */
class ApiErrorHandler {
    /**
     * API 오류를 처리합니다
     * @param {Error} error - 발생한 오류
     * @param {string} context - 오류 발생 컨텍스트
     */
    static handle(error, context = '') {
        console.error(`${context} 오류:`, error);
        
        if (error.message.includes('401')) {
            return this.handleUnauthorized();
        }
        
        if (error.message.includes('403')) {
            return this.handleForbidden();
        }
        
        if (error.message.includes('404')) {
            return this.handleNotFound();
        }
        
        if (error.message.includes('500')) {
            return this.handleServerError();
        }
        
        if (this.isNetworkError(error)) {
            return this.handleNetworkError();
        }
        
        this.handleUnknownError(error);
    }

    /**
     * 401 Unauthorized 처리
     */
    static handleUnauthorized() {
        console.log('인증 토큰이 유효하지 않습니다. 로그인 페이지로 이동합니다.');
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    }

    /**
     * 403 Forbidden 처리
     */
    static handleForbidden() {
        alert('접근 권한이 없습니다.');
    }

    /**
     * 404 Not Found 처리
     */
    static handleNotFound() {
        console.error('API 엔드포인트를 찾을 수 없습니다.');
    }

    /**
     * 500 Server Error 처리
     */
    static handleServerError() {
        alert('서버 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
    }

    /**
     * 네트워크 오류인지 확인
     * @param {Error} error - 확인할 오류
     * @returns {boolean} 네트워크 오류 여부
     */
    static isNetworkError(error) {
        return error.name === 'TypeError' && error.message.includes('fetch');
    }

    /**
     * 네트워크 오류 처리
     */
    static handleNetworkError() {
        alert('네트워크 연결을 확인해주세요.');
    }

    /**
     * 알 수 없는 오류 처리
     * @param {Error} error - 발생한 오류
     */
    static handleUnknownError(error) {
        console.error('예상치 못한 오류:', error);
    }

    /**
     * 오류가 401인지 확인
     * @param {Error} error - 확인할 오류
     * @returns {boolean} 401 오류 여부
     */
    static is401Error(error) {
        return error.message.includes('401');
    }
}
</script>