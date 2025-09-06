{{-- 토큰 관리 공통 함수 --}}
<script>
/**
 * 인증 토큰을 관리하는 공통 함수들
 */

/**
 * 토큰을 저장합니다
 * @param {string} token - 저장할 토큰
 */
function setAuthToken(token) {
    localStorage.setItem('auth_token', token);
}

/**
 * 토큰을 가져옵니다
 * @returns {string|null} 저장된 토큰 또는 null
 */
function getAuthToken() {
    return localStorage.getItem('auth_token');
}

/**
 * CSRF 토큰을 가져옵니다
 * @returns {string} CSRF 토큰
 */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value
        || '';
}

/**
 * 토큰을 제거합니다
 */
function removeAuthToken() {
    localStorage.removeItem('auth_token');
}

/**
 * 토큰 존재 여부를 확인합니다
 * @returns {boolean} 토큰 존재 여부
 */
function hasAuthToken() {
    return !!localStorage.getItem('auth_token');
}

/**
 * 인증 헤더를 가져옵니다 (토큰이 있을 때)
 * @returns {object} 인증 헤더 객체
 */
function getAuthHeaders() {
    const token = getAuthToken();
    return {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
}

/**
 * API 요청에 사용할 통합 헤더를 반환합니다
 * 토큰이 있으면 Bearer 토큰, 없으면 CSRF 토큰 사용
 * @param {object} customHeaders - 추가 헤더
 * @returns {object} 완전한 헤더 객체
 */
function getApiHeaders(customHeaders = {}) {
    const token = getAuthToken();
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest', // AJAX 요청임을 명시
        ...customHeaders
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    } else {
        // CSRF 토큰 사용 (웹 세션)
        const csrfToken = getCsrfToken();
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
    }

    return headers;
}

/**
 * 표준 헤더를 가져옵니다 (폴백 로직 포함)
 * API 클라이언트에서 사용하는 표준 헤더 생성 함수
 * @param {object} customHeaders - 추가 헤더
 * @returns {object} 완전한 헤더 객체
 */
function getStandardHeaders(customHeaders = {}) {
    return typeof getApiHeaders === 'function' 
        ? getApiHeaders(customHeaders)
        : {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...customHeaders
        };
}

/**
 * 표준 fetch 요청 래퍼 (공통 AJAX 함수가 없을 때 사용)
 * @param {string} url - 요청 URL
 * @param {object} options - fetch 옵션
 * @returns {Promise<object>} 응답 데이터
 */
async function fetchWithAuth(url, options = {}) {
    const defaultOptions = {
        headers: getApiHeaders(),
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

    const response = await fetch(url, defaultOptions);
    
    if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        throw new Error(errorData.message || `서버 오류: ${response.status}`);
    }
    
    return response.json();
}
</script>