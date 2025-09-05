{{-- AJAX PUT 요청 함수 --}}
<script>
/**
 * PUT 요청을 수행합니다
 * @param {string} url - 요청 URL
 * @param {Object} data - 요청 데이터
 * @param {Object} options - 추가 fetch 옵션
 * @returns {Promise<Object>} JSON 응답
 */
async function ajaxPut(url, data = {}, options = {}) {
    const token = localStorage.getItem('auth_token');
    
    const defaultOptions = {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    };

    if (token) {
        defaultOptions.headers['Authorization'] = `Bearer ${token}`;
    }

    const finalOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    };

    const response = await fetch(url, finalOptions);

    if (!response.ok) {
        throw new Error(`API 오류: ${response.status}`);
    }

    return response.json();
}
</script>