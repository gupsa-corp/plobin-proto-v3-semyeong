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
 * 인증 헤더를 가져옵니다
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
</script>