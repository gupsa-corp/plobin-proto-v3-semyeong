{{-- 비밀번호 재설정 처리 공통 함수 --}}
<script>
/**
 * 비밀번호 찾기(재설정 링크 전송)를 담당하는 공통 함수
 * @param {string} email - 이메일 주소
 * @returns {Promise<object>} 요청 결과
 */
async function handleForgotPassword(email) {
    try {
        // 공통 AJAX POST 함수 사용
        let data;
        if (typeof ajaxPost === 'function') {
            data = await ajaxPost('/api/auth/forgot-password', { email: email }, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
        } else {
            // fallback: 직접 fetch 사용
            const response = await fetch('/api/auth/forgot-password', {
                method: 'POST',
                headers: getApiHeaders({ 'X-Requested-With': 'XMLHttpRequest' }),
                body: JSON.stringify({ email: email })
            });
            data = await response.json();
        }

        if (data.success) {
            return {
                success: true,
                message: data.message || '비밀번호 재설정 링크가 이메일로 전송되었습니다.'
            };
        } else {
            let errorMsg = '';

            if (data.errors && data.errors.email) {
                errorMsg = data.errors.email[0];
            } else {
                errorMsg = data.message || '요청 처리 중 오류가 발생했습니다.';
            }

            return {
                success: false,
                message: errorMsg
            };
        }

    } catch (error) {
        console.error('비밀번호 찾기 요청 중 오류:', error);

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
 * 비밀번호 재설정을 담당하는 공통 함수
 * @param {object} resetData - 재설정 데이터 (token, email, password, password_confirmation)
 * @returns {Promise<object>} 재설정 결과
 */
async function handleResetPassword(resetData) {
    try {
        // 공통 AJAX POST 함수 사용
        let data;
        if (typeof ajaxPost === 'function') {
            data = await ajaxPost('/api/auth/reset-password', resetData, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
        } else {
            // fallback: 직접 fetch 사용
            const response = await fetch('/api/auth/reset-password', {
                method: 'POST',
                headers: getApiHeaders({ 'X-Requested-With': 'XMLHttpRequest' }),
                body: JSON.stringify(resetData)
            });
            data = await response.json();
        }

        if (data.success) {
            return {
                success: true,
                message: data.message || '비밀번호가 성공적으로 변경되었습니다.',
                redirectUrl: '/login'
            };
        } else {
            let errorMsg = '';

            if (data.errors) {
                // Laravel validation errors
                const errorKeys = Object.keys(data.errors);
                if (errorKeys.length > 0) {
                    errorMsg = data.errors[errorKeys[0]][0];
                }
            } else {
                errorMsg = data.message || '비밀번호 재설정에 실패했습니다.';
            }

            return {
                success: false,
                message: errorMsg,
                errors: data.errors || {}
            };
        }

    } catch (error) {
        console.error('비밀번호 재설정 요청 중 오류:', error);

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
 * 비밀번호 찾기 폼 유효성 검사
 * @param {string} email - 이메일
 * @returns {object} 유효성 검사 결과
 */
function validateForgotPasswordForm(email) {
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

    return {
        valid: true,
        message: null
    };
}

/**
 * 비밀번호 재설정 폼 유효성 검사
 * @param {object} resetData - 재설정 데이터
 * @returns {object} 유효성 검사 결과
 */
function validateResetPasswordForm(resetData) {
    const { token, email, password, password_confirmation } = resetData;

    if (!token) {
        return {
            valid: false,
            message: '유효하지 않은 재설정 토큰입니다.'
        };
    }

    if (!email) {
        return {
            valid: false,
            message: '이메일이 필요합니다.'
        };
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return {
            valid: false,
            message: '올바른 이메일 형식을 입력해주세요.'
        };
    }

    if (!password) {
        return {
            valid: false,
            message: '새 비밀번호를 입력해주세요.'
        };
    }

    if (password.length < 6) {
        return {
            valid: false,
            message: '비밀번호는 최소 6자 이상이어야 합니다.'
        };
    }

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