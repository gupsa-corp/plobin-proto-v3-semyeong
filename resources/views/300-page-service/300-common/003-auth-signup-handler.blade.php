{{-- 회원가입 처리 공통 함수 --}}
<script>
/**
 * 이메일 중복 확인을 담당하는 공통 함수
 * @param {string} email - 확인할 이메일
 * @returns {Promise<object>} 이메일 확인 결과
 */
async function checkEmailAvailability(email) {
    try {
        // 공통 AJAX POST 함수 사용
        let data;
        if (typeof ajaxPost === 'function') {
            data = await ajaxPost('/api/auth/check-email', { email: email }, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
        } else {
            // fallback: 직접 fetch 사용
            const response = await fetch('/api/auth/check-email', {
                method: 'POST',
                headers: getApiHeaders({ 'X-Requested-With': 'XMLHttpRequest' }),
                body: JSON.stringify({ email: email })
            });
            data = await response.json();
        }

        return {
            success: true,
            available: data.available || false,
            message: data.message || ''
        };

    } catch (error) {
        console.error('이메일 확인 요청 중 오류:', error);
        return {
            success: false,
            available: false,
            message: '네트워크 오류가 발생했습니다.'
        };
    }
}

/**
 * 회원가입 처리를 담당하는 공통 함수
 * @param {object} userData - 회원가입 데이터
 * @returns {Promise<object>} 회원가입 결과
 */
async function handleSignup(userData) {
    try {
        // 공통 AJAX POST 함수 사용
        let data;
        if (typeof ajaxPost === 'function') {
            data = await ajaxPost('/api/auth/signup', userData, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
        } else {
            // fallback: 직접 fetch 사용
            const response = await fetch('/api/auth/signup', {
                method: 'POST',
                headers: getApiHeaders({ 'X-Requested-With': 'XMLHttpRequest' }),
                body: JSON.stringify(userData)
            });
            data = await response.json();
        }

        if (data.success) {
            // 토큰이 있다면 저장
            if (data.data && data.data.token) {
                setAuthToken(data.data.token);
            }

            return {
                success: true,
                data: data.data,
                message: data.message || '회원가입이 완료되었습니다.',
                redirectUrl: data.redirect_url || '/dashboard'
            };
        } else {
            // 회원가입 실패
            let errorMsg = '';

            if (data.errors) {
                // Laravel validation errors
                const errorKeys = Object.keys(data.errors);
                if (errorKeys.length > 0) {
                    errorMsg = data.errors[errorKeys[0]][0];
                }
            } else {
                errorMsg = data.message || '회원가입에 실패했습니다.';
            }

            return {
                success: false,
                message: errorMsg,
                errors: data.errors || {}
            };
        }

    } catch (error) {
        console.error('회원가입 요청 중 오류:', error);

        let errorMsg = '네트워크 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
        
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
            errorMsg = '서버에 연결할 수 없습니다. 네트워크 연결을 확인해주세요.';
        }

        return {
            success: false,
            message: errorMsg
        };
    }
}

/**
 * 회원가입 폼 유효성 검사
 * @param {object} userData - 검사할 사용자 데이터
 * @returns {object} 유효성 검사 결과
 */
function validateSignupForm(userData) {
    const { name, email, password, password_confirmation } = userData;

    // 이름 검사
    if (!name || name.trim().length < 2) {
        return {
            valid: false,
            message: '이름은 최소 2자 이상 입력해주세요.'
        };
    }

    // 이메일 검사
    if (!email) {
        return {
            valid: false,
            message: '이메일을 입력해주세요.'
        };
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return {
            valid: false,
            message: '올바른 이메일 형식을 입력해주세요.'
        };
    }

    // 비밀번호 검사
    if (!password) {
        return {
            valid: false,
            message: '비밀번호를 입력해주세요.'
        };
    }

    if (password.length < 6) {
        return {
            valid: false,
            message: '비밀번호는 최소 6자 이상이어야 합니다.'
        };
    }

    // 비밀번호 확인 검사
    if (password !== password_confirmation) {
        return {
            valid: false,
            message: '비밀번호가 일치하지 않습니다.'
        };
    }

    return {
        valid: true,
        message: null
    };
}
</script>