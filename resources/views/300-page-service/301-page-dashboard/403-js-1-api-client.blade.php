{{-- API 클라이언트 --}}
<script>
/**
 * API 호출을 담당하는 클라이언트 클래스
 * 기능: 인증 토큰 관리, HTTP 요청 처리, 에러 핸들링
 */
class ApiClient {
    constructor() {
        this.baseUrl = '/api';
        this.defaultHeaders = {
            'Content-Type': 'application/json'
        };
    }

    // 인증 토큰 가져오기
    getAuthToken() {
        return localStorage.getItem('auth_token') || '';
    }

    // CSRF 토큰 가져오기
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    // 기본 헤더 생성
    getHeaders(customHeaders = {}) {
        const token = this.getAuthToken();
        const headers = {
            ...this.defaultHeaders,
            ...customHeaders
        };

        // Bearer 토큰이 있으면 Authorization 헤더 추가
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        } else {
            // 토큰이 없으면 CSRF 토큰 사용 (웹 세션)
            headers['X-CSRF-TOKEN'] = this.getCsrfToken();
        }

        return headers;
    }

    // API 요청 처리
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        const config = {
            credentials: 'include',
            headers: this.getHeaders(options.headers),
            ...options
        };

        try {
            const response = await fetch(url, config);
            const contentType = response.headers.get('content-type');
            
            // JSON 응답 처리
            if (contentType && contentType.includes('application/json')) {
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || `HTTP Error: ${response.status}`);
                }
                
                return result;
            }
            
            // HTML 응답 처리 (리디렉트 등)
            if (response.ok) {
                return { success: true, data: null };
            } else {
                throw new Error('예상하지 못한 응답 형식입니다.');
            }
        } catch (error) {
            console.error(`API 요청 실패 (${endpoint}):`, error);
            throw error;
        }
    }

    // GET 요청
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        
        return this.request(url, { method: 'GET' });
    }

    // POST 요청
    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // PUT 요청
    async put(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // DELETE 요청
    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }
}

// 전역 API 클라이언트 인스턴스
window.apiClient = new ApiClient();
</script>