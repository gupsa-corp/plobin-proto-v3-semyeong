{{-- HTTP 요청 관리 모듈 --}}
<script>
/**
 * HTTP 요청 관리 모듈
 * API 헤더 생성, HTTP 요청 처리
 */
class AuthHttpModule {
    constructor(authManager) {
        this.authManager = authManager;
    }

    /**
     * API 헤더 생성
     */
    getApiHeaders(customHeaders = {}) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...customHeaders
        };

        if (this.authManager.token) {
            headers['Authorization'] = `Bearer ${this.authManager.token}`;
        } else {
            // CSRF 토큰 사용 (웹 세션)
            const csrfToken = this.getCsrfToken();
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
        }

        return headers;
    }

    /**
     * CSRF 토큰 가져오기
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || document.querySelector('input[name="_token"]')?.value
            || '';
    }

    /**
     * 통합 HTTP 요청 함수
     */
    async makeRequest(url, options = {}) {
        const defaultOptions = {
            headers: this.getApiHeaders(),
            credentials: 'include',
            ...options
        };

        // 헤더 병합
        if (options.headers) {
            defaultOptions.headers = {
                ...defaultOptions.headers,
                ...options.headers
            };
        }

        try {
            const response = await fetch(url, defaultOptions);
            
            // 401 에러 시 자동 로그아웃
            if (response.status === 401) {
                this.authManager.utilityModule.handleUnauthorized();
                throw new Error('인증이 필요합니다.');
            }
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `서버 오류: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            // 네트워크 오류 처리
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                throw new Error('서버에 연결할 수 없습니다. 네트워크 연결을 확인해주세요.');
            }
            throw error;
        }
    }
}

window.AuthHttpModule = AuthHttpModule;
</script>