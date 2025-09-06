{{-- 중앙집중식 API 클라이언트 --}}
<script>
/**
 * API 호출을 담당하는 클라이언트 클래스
 * 기능: HTTP 요청 처리 (중앙집중식 AuthManager 활용)
 */
class ApiClient {
    constructor() {
        this.baseUrl = '/api';
        this.authManager = window.AuthManager;
    }

    // API 요청 처리 (AuthManager의 makeRequest 사용)
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        return this.authManager.makeRequest(url, options);
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

    // 인증 상태 확인
    isAuthenticated() {
        return this.authManager.isAuthenticated();
    }

    // 현재 사용자 정보 가져오기
    getCurrentUser() {
        return this.authManager.getUser();
    }
}

// 전역 API 클라이언트 인스턴스
window.apiClient = new ApiClient();

console.log('중앙집중식 ApiClient 로드 완료');
</script>