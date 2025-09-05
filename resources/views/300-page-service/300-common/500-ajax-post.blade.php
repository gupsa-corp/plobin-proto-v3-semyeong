{{-- AJAX POST 요청 함수 --}}
<script>
/**
 * POST 요청을 수행합니다
 * @param {string} url - 요청 URL
 * @param {Object} data - 요청 데이터
 * @param {Object} options - 추가 fetch 옵션
 * @returns {Promise<Object>} JSON 응답
 */
async function ajaxPost(url, data = {}, options = {}) {
    const authHeaders = getAuthHeaders();
    
    const defaultOptions = {
        method: 'POST',
        headers: authHeaders,
        body: JSON.stringify(data)
    };

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
        const errorData = await response.json().catch(() => ({}));
        let errorMessage = errorData.message || `서버 오류가 발생했습니다.`;
        
        // 422 유효성 검사 오류 처리
        if (response.status === 422 && errorData.errors) {
            const errors = Object.values(errorData.errors).flat();
            errorMessage = errors.length > 0 ? errors[0] : errorMessage;
        }
        
        throw new Error(errorMessage);
    }

    return response.json();
}
</script>