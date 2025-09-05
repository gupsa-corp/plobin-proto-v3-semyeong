{{-- AJAX DELETE 요청 함수 --}}
<script>
/**
 * DELETE 요청을 수행합니다
 * @param {string} url - 요청 URL
 * @param {Object} options - 추가 fetch 옵션
 * @returns {Promise<Object>} JSON 응답
 */
async function ajaxDelete(url, options = {}) {
    const token = localStorage.getItem('auth_token');
    
    const defaultOptions = {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
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