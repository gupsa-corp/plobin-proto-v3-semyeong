{{-- 로그인 처리 공통 함수 --}}
<script>
/**
 * 로그인 처리를 담당하는 공통 함수
 * @param {string} email - 이메일
 * @param {string} password - 비밀번호
 * @param {boolean} remember - 로그인 유지 여부
 * @returns {Promise<object>} 로그인 결과
 */
async function handleLogin(email, password, remember = false) {
    try {
        // 공통 AJAX POST 함수 사용
        let data;
        if (typeof ajaxPost === 'function') {
            data = await ajaxPost('/api/auth/login', {
                email: email,
                password: password,
                remember: remember
            }, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        } else {
            // fallback: 직접 fetch 사용
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: getApiHeaders({ 'X-Requested-With': 'XMLHttpRequest' }),
                body: JSON.stringify({
                    email: email,
                    password: password,
                    remember: remember
                })
            });
            data = await response.json();
        }

        if (data.success || data.token || (data.data && data.data.token)) {
            // 토큰이 있으면 저장 (data.token 또는 data.data.token 확인)
            const token = data.token || (data.data && data.data.token);
            if (token) {
                setAuthToken(token);
                // AuthHelper에도 설정
                if (window.AuthHelper) {
                    window.AuthHelper.setToken(token);
                }
            }

            // 쿠키 기반 인증의 경우 토큰 없이도 성공
            const user = data.user || (data.data && data.data.user);
            if (user) {
                console.log('로그인한 사용자:', user);
            }

            // 성공 응답
            return {
                success: true,
                data: data,
                redirectUrl: data.redirect_url || (data.data && data.data.redirect_url) || '/dashboard'
            };
        } else {
            // 로그인 실패
            let errorMsg = '';

            if (data.errors) {
                // Laravel validation errors
                if (data.errors.email) {
                    errorMsg = data.errors.email[0];
                } else if (data.errors.password) {
                    errorMsg = data.errors.password[0];
                } else {
                    errorMsg = Object.values(data.errors)[0][0] || '입력 정보를 확인해주세요.';
                }
            } else {
                errorMsg = data.message || '로그인에 실패했습니다. 이메일과 비밀번호를 확인해주세요.';
            }

            return {
                success: false,
                message: errorMsg
            };
        }

    } catch (error) {
        console.error('로그인 요청 중 오류:', error);

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
 * 로그인 폼 유효성 검사
 * @param {string} email - 이메일
 * @param {string} password - 비밀번호
 * @returns {object} 유효성 검사 결과
 */
function validateLoginForm(email, password) {
    if (!email || !password) {
        return {
            valid: false,
            message: '이메일과 비밀번호를 모두 입력해주세요.'
        };
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return {
            valid: false,
            message: '올바른 이메일 형식을 입력해주세요.'
        };
    }

    if (password.length < 6) {
        return {
            valid: false,
            message: '비밀번호는 최소 6자 이상이어야 합니다.'
        };
    }

    return {
        valid: true,
        message: null
    };
}
</script>