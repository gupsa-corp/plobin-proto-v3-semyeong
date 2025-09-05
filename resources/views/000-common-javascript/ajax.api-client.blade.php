{{-- API 클라이언트 공통 유틸리티 --}}
<script>
/**
 * API 호출을 담당하는 공통 유틸리티
 */
class ApiClient {
    /**
     * 기본 fetch 요청을 수행합니다
     * @param {string} url - 요청 URL
     * @param {Object} options - fetch 옵션
     * @returns {Promise<Response>}
     */
    static async fetch(url, options = {}) {
        const token = localStorage.getItem('auth_token');
        
        const defaultOptions = {
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

        return fetch(url, finalOptions);
    }

    /**
     * GET 요청을 수행합니다
     * @param {string} url - 요청 URL
     * @param {Object} options - 추가 fetch 옵션
     * @returns {Promise<Object>} JSON 응답
     */
    static async get(url, options = {}) {
        const response = await this.fetch(url, {
            method: 'GET',
            ...options
        });

        if (!response.ok) {
            throw new Error(`API 오류: ${response.status}`);
        }

        return response.json();
    }

    /**
     * POST 요청을 수행합니다
     * @param {string} url - 요청 URL
     * @param {Object} data - 요청 데이터
     * @param {Object} options - 추가 fetch 옵션
     * @returns {Promise<Object>} JSON 응답
     */
    static async post(url, data = {}, options = {}) {
        const response = await this.fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
            ...options
        });

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

    /**
     * PUT 요청을 수행합니다
     * @param {string} url - 요청 URL
     * @param {Object} data - 요청 데이터
     * @param {Object} options - 추가 fetch 옵션
     * @returns {Promise<Object>} JSON 응답
     */
    static async put(url, data = {}, options = {}) {
        const response = await this.fetch(url, {
            method: 'PUT',
            body: JSON.stringify(data),
            ...options
        });

        if (!response.ok) {
            throw new Error(`API 오류: ${response.status}`);
        }

        return response.json();
    }

    /**
     * DELETE 요청을 수행합니다
     * @param {string} url - 요청 URL
     * @param {Object} options - 추가 fetch 옵션
     * @returns {Promise<Object>} JSON 응답
     */
    static async delete(url, options = {}) {
        const response = await this.fetch(url, {
            method: 'DELETE',
            ...options
        });

        if (!response.ok) {
            throw new Error(`API 오류: ${response.status}`);
        }

        return response.json();
    }
}
</script>