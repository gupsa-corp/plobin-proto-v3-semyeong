{{-- API 클라이언트 --}}
<script>
/**
 * API 호출을 담당하는 클라이언트 클래스
 * 기능: HTTP 요청 처리 (인증은 300-에서 처리됨)
 */
class ApiClient {
    constructor() {
        this.baseUrl = '/api';
    }

    // API 요청 처리 (300-에서 제공하는 공통 함수 활용)
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        return fetchWithAuth(url, options);
    }

    // GET 요청 (300-에서 제공하는 공통 함수 사용)
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return ajaxGet(`${this.baseUrl}${url}`);
    }

    // POST 요청 (300-에서 제공하는 공통 함수 사용)
    async post(endpoint, data = {}) {
        return ajaxPost(`${this.baseUrl}${endpoint}`, data);
    }

    // PUT 요청 (300-에서 제공하는 공통 함수 사용)
    async put(endpoint, data = {}) {
        return ajaxPut(`${this.baseUrl}${endpoint}`, data);
    }

    // DELETE 요청 (300-에서 제공하는 공통 함수 사용)
    async delete(endpoint) {
        return ajaxDelete(`${this.baseUrl}${endpoint}`);
    }
}

// 전역 API 클라이언트 인스턴스
window.apiClient = new ApiClient();
</script>