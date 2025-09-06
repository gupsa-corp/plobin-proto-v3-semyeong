<script>
// 로그인 AJAX 처리 스크립트
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const errorText = document.getElementById('errorText');

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // 메시지 숨기기
            hideMessages();

            // 폼 데이터 수집
            const formData = new FormData(loginForm);
            const email = formData.get('email');
            const password = formData.get('password');

            // 유효성 검사 (중앙화된 함수 사용)
            if (typeof validateLoginForm === 'function') {
                const validation = validateLoginForm(email, password);
                if (!validation.valid) {
                    showError(validation.message);
                    return;
                }
            } else {
                // fallback 유효성 검사
                if (!email || !password) {
                    showError('이메일과 비밀번호를 모두 입력해주세요.');
                    return;
                }
            }

            // 로그인 버튼 비활성화
            const submitButton = loginForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = '로그인 중...';
            submitButton.classList.add('btn-loading');

            try {
                // 중앙화된 로그인 함수 사용
                if (typeof handleLogin === 'function') {
                    const result = await handleLogin(email, password, formData.get('remember-me') ? true : false);
                    
                    if (result.success) {
                        // 로그인 성공
                        window.location.href = result.redirectUrl;
                    } else {
                        // 로그인 실패
                        showError(result.message);
                    }
                } else {
                    // handleLogin 함수가 없는 경우 fallback
                    showError('로그인 처리 함수를 찾을 수 없습니다. 페이지를 새로고침해주세요.');
                }

            } catch (error) {
                console.error('로그인 처리 중 오류:', error);
                showError('로그인 처리 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
            } finally {
                // 버튼 다시 활성화
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                submitButton.classList.remove('btn-loading');
            }
        });
    }

    // 메시지 표시/숨기기 함수들
    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('hidden');
        errorMessage.classList.add('message-enter');
        successMessage.classList.add('hidden');

        // 메시지 영역으로 스크롤
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function showSuccess(message) {
        const successText = successMessage.querySelector('p');
        if (successText) {
            successText.textContent = message;
        }
        successMessage.classList.remove('hidden');
        successMessage.classList.add('message-enter');
        errorMessage.classList.add('hidden');

        // 메시지 영역으로 스크롤
        successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }



    function hideMessages() {
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
    }

    // 입력 필드 포커스 시 에러 메시지 숨기기
    const inputs = loginForm?.querySelectorAll('input[type="email"], input[type="password"]');
    inputs?.forEach(input => {
        input.addEventListener('focus', hideMessages);

        // Enter 키 처리
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                loginForm.requestSubmit();
            }
        });
    });

    // 폼 리셋 시 메시지 숨기기
    loginForm?.addEventListener('reset', hideMessages);

    console.log('로그인 AJAX 스크립트 로드 완료');
});

// 글로벌 handleLogin 함수
window.handleLogin = async function(email, password, remember = false) {
    try {
        // AuthManager의 login 메서드 사용
        const result = await window.AuthManager.login(email, password, remember);
        return result;
    } catch (error) {
        console.error('handleLogin 오류:', error);
        return {
            success: false,
            message: error.message || '로그인 처리 중 오류가 발생했습니다.'
        };
    }
};

// 글로벌 validateLoginForm 함수
window.validateLoginForm = function(email, password) {
    // AuthManager의 validateLoginForm 메서드 사용
    return window.AuthManager.validateLoginForm(email, password);
};

// AJAX 응답 상태 코드별 처리
function handleAjaxError(xhr, status, error) {
    console.error('AJAX Error:', { xhr, status, error });

    let message = '알 수 없는 오류가 발생했습니다.';

    if (xhr.status === 401) {
        message = '인증에 실패했습니다. 이메일과 비밀번호를 확인해주세요.';
    } else if (xhr.status === 422) {
        message = '입력 정보가 올바르지 않습니다.';
    } else if (xhr.status === 429) {
        message = '너무 많은 시도를 했습니다. 잠시 후 다시 시도해주세요.';
    } else if (xhr.status === 500) {
        message = '서버 오류가 발생했습니다. 관리자에게 문의해주세요.';
    } else if (xhr.status === 0) {
        message = '네트워크 연결을 확인해주세요.';
    }

    return message;
}
</script>
