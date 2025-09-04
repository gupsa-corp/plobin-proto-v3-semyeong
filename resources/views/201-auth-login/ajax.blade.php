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
            
            // 기본 유효성 검사
            if (!email || !password) {
                showError('이메일과 비밀번호를 모두 입력해주세요.');
                return;
            }
            
            // 로그인 버튼 비활성화
            const submitButton = loginForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = '로그인 중...';
            submitButton.classList.add('btn-loading');
            
            try {
                // CSRF 토큰 가져오기
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;
                
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember: formData.get('remember-me') ? true : false
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && (data.success || data.token)) {
                    // 로그인 성공
                    showSuccess('로그인 성공! 잠시 후 대시보드로 이동합니다...');
                    
                    // 토큰이 있으면 저장
                    if (data.token) {
                        localStorage.setItem('auth_token', data.token);
                        // AuthHelper에도 설정
                        if (window.AuthHelper) {
                            window.AuthHelper.setToken(data.token);
                        }
                    }
                    
                    // 쿠키 기반 인증의 경우 토큰 없이도 성공
                    if (data.user) {
                        console.log('로그인한 사용자:', data.user);
                    }
                    
                    // 대시보드로 리다이렉트 (2초 후)
                    setTimeout(() => {
                        const redirectUrl = data.redirect_url || data.redirect || '/dashboard';
                        window.location.href = redirectUrl;
                    }, 2000);
                    
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
                    
                    showError(errorMsg);
                }
                
            } catch (error) {
                console.error('로그인 AJAX 요청 중 오류:', error);
                
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    showError('서버에 연결할 수 없습니다. 네트워크 연결을 확인해주세요.');
                } else {
                    showError('네트워크 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
                }
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
    
    function showSuccess(message = null) {
        if (message) {
            const successText = successMessage.querySelector('.text-green-700');
            if (successText) successText.textContent = message;
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