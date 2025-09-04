<script>
// 비밀번호 재설정 AJAX 처리 스크립트
document.addEventListener('DOMContentLoaded', function() {
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const loadingMessage = document.getElementById('loadingMessage');
    const tokenExpiredMessage = document.getElementById('tokenExpiredMessage');
    const errorText = document.getElementById('errorText');
    const successText = document.getElementById('successText');

    // URL에서 토큰과 이메일 파라미터 확인
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const email = urlParams.get('email');

    // 토큰이나 이메일이 없으면 만료된 것으로 간주
    if (!token || !email) {
        showTokenExpired();
        return;
    }

    // 폼에 이메일 설정
    const emailInput = document.getElementById('email');
    if (emailInput && email) {
        emailInput.value = decodeURIComponent(email);
    }

    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // 메시지 숨기기
            hideMessages();
            
            // 폼 데이터 수집
            const formData = new FormData(resetPasswordForm);
            const password = formData.get('password');
            const passwordConfirmation = formData.get('password_confirmation');
            const emailValue = formData.get('email');
            const tokenValue = formData.get('token');
            
            // 기본 유효성 검사
            if (!emailValue || !password || !passwordConfirmation || !tokenValue) {
                showError('모든 필드를 입력해주세요.');
                return;
            }
            
            // 비밀번호 확인
            if (password !== passwordConfirmation) {
                showError('비밀번호가 일치하지 않습니다.');
                return;
            }
            
            // 비밀번호 길이 검사
            if (password.length < 8) {
                showError('비밀번호는 8자 이상이어야 합니다.');
                return;
            }
            
            // 비밀번호 복잡도 검사 (영문자와 숫자 조합)
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                showError('비밀번호는 영문자와 숫자를 포함해야 합니다.');
                return;
            }
            
            // 이메일 형식 검사
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                showError('올바른 이메일 주소 형식을 입력해주세요.');
                return;
            }
            
            // 버튼 비활성화 및 로딩 표시
            const submitButton = document.getElementById('submitButton');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="animate-spin h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                재설정 중...
            `;
            
            // 로딩 메시지 표시
            showLoading();
            
            try {
                // CSRF 토큰 가져오기
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;
                
                const response = await fetch('/api/auth/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: emailValue,
                        password: password,
                        password_confirmation: passwordConfirmation,
                        token: tokenValue
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // 비밀번호 재설정 성공
                    const successMsg = data.message || '비밀번호가 성공적으로 재설정되었습니다.\n새 비밀번호로 로그인해 주세요.';
                    showSuccess(successMsg);
                    
                    // 폼 리셋
                    resetPasswordForm.reset();
                    
                    // 3초 후 로그인 페이지로 이동
                    setTimeout(() => {
                        window.location.href = '/login?message=비밀번호가 재설정되었습니다. 새 비밀번호로 로그인해 주세요.';
                    }, 3000);
                    
                } else {
                    // 비밀번호 재설정 실패
                    let errorMsg = '';
                    
                    if (response.status === 400 || response.status === 422) {
                        if (data.errors) {
                            // Laravel validation errors
                            if (data.errors.email) {
                                errorMsg = data.errors.email[0];
                            } else if (data.errors.password) {
                                errorMsg = data.errors.password[0];
                            } else if (data.errors.token) {
                                errorMsg = '재설정 링크가 유효하지 않거나 만료되었습니다.';
                                showTokenExpired();
                                return;
                            } else {
                                errorMsg = Object.values(data.errors)[0][0] || '입력 정보를 확인해주세요.';
                            }
                        } else {
                            errorMsg = data.message || '비밀번호 재설정에 실패했습니다.';
                        }
                    } else if (response.status === 404 || response.status === 401) {
                        errorMsg = '재설정 링크가 유효하지 않거나 만료되었습니다.';
                        showTokenExpired();
                        return;
                    } else {
                        errorMsg = data.message || '비밀번호 재설정에 실패했습니다. 다시 시도해주세요.';
                    }
                    
                    showError(errorMsg);
                }
                
            } catch (error) {
                console.error('비밀번호 재설정 AJAX 요청 중 오류:', error);
                
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    showError('서버에 연결할 수 없습니다. 네트워크 연결을 확인해주세요.');
                } else {
                    showError('네트워크 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
                }
            } finally {
                // 버튼 다시 활성화
                submitButton.disabled = false;
                submitButton.innerHTML = `
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-primary-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3a1 1 0 011-1h2.586l6.414-6.414a6 6 0 015.743-7.743z"></path>
                        </svg>
                    </span>
                    비밀번호 재설정
                `;
                
                // 로딩 메시지 숨기기
                hideLoading();
            }
        });
    }
    
    // 메시지 표시/숨기기 함수들
    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('hidden');
        errorMessage.classList.add('message-enter');
        successMessage.classList.add('hidden');
        tokenExpiredMessage.classList.add('hidden');
        
        // 메시지 영역으로 스크롤
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function showSuccess(message) {
        successText.innerHTML = message.replace(/\n/g, '<br>');
        successMessage.classList.remove('hidden');
        successMessage.classList.add('message-enter');
        errorMessage.classList.add('hidden');
        tokenExpiredMessage.classList.add('hidden');
        
        // 메시지 영역으로 스크롤
        successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function showLoading() {
        loadingMessage.classList.remove('hidden');
        loadingMessage.classList.add('message-enter');
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
        tokenExpiredMessage.classList.add('hidden');
    }
    
    function showTokenExpired() {
        tokenExpiredMessage.classList.remove('hidden');
        tokenExpiredMessage.classList.add('message-enter');
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
        loadingMessage.classList.add('hidden');
        
        // 폼 비활성화
        if (resetPasswordForm) {
            const inputs = resetPasswordForm.querySelectorAll('input, button');
            inputs.forEach(input => input.disabled = true);
        }
    }
    
    function hideLoading() {
        loadingMessage.classList.add('hidden');
    }
    
    function hideMessages() {
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
        loadingMessage.classList.add('hidden');
        tokenExpiredMessage.classList.add('hidden');
    }
    
    // 입력 필드 포커스 시 에러 메시지 숨기기 및 실시간 검증
    const inputs = resetPasswordForm?.querySelectorAll('input');
    inputs?.forEach(input => {
        input.addEventListener('focus', hideMessages);
        
        // Enter 키 처리
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                resetPasswordForm.requestSubmit();
            }
        });
        
        // 실시간 검증
        if (input.type === 'password') {
            input.addEventListener('input', function(e) {
                const password = e.target.value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                
                // 비밀번호 확인 필드가 있고 값이 있을 때만 검증
                if (input.id === 'password_confirmation' && password && document.getElementById('password').value) {
                    if (password !== document.getElementById('password').value) {
                        e.target.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                        e.target.classList.remove('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                    } else {
                        e.target.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                        e.target.classList.add('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                    }
                } else if (input.id === 'password' && passwordConfirmation && password) {
                    const confirmInput = document.getElementById('password_confirmation');
                    if (password !== passwordConfirmation) {
                        confirmInput.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                        confirmInput.classList.remove('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                    } else {
                        confirmInput.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                        confirmInput.classList.add('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                    }
                }
            });
        }
        
        // 이메일 실시간 검증
        if (input.type === 'email') {
            input.addEventListener('input', function(e) {
                const email = e.target.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    e.target.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                    e.target.classList.remove('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                } else {
                    e.target.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                    e.target.classList.add('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                }
            });
        }
    });
    
    // 폼 리셋 시 메시지 숨기기
    resetPasswordForm?.addEventListener('reset', hideMessages);
    
    console.log('비밀번호 재설정 AJAX 스크립트 로드 완료');
});

// AJAX 응답 상태 코드별 처리
function handleResetPasswordAjaxError(xhr, status, error) {
    console.error('Reset Password AJAX Error:', { xhr, status, error });
    
    let message = '알 수 없는 오류가 발생했습니다.';
    
    if (xhr.status === 400) {
        message = '잘못된 요청입니다. 입력 정보를 확인해주세요.';
    } else if (xhr.status === 401 || xhr.status === 404) {
        message = '재설정 링크가 유효하지 않거나 만료되었습니다.';
    } else if (xhr.status === 422) {
        message = '입력 정보가 올바르지 않습니다.';
    } else if (xhr.status === 429) {
        message = '너무 많은 요청을 보냈습니다. 잠시 후 다시 시도해주세요.';
    } else if (xhr.status === 500) {
        message = '서버 오류가 발생했습니다. 관리자에게 문의해주세요.';
    } else if (xhr.status === 0) {
        message = '네트워크 연결을 확인해주세요.';
    }
    
    return message;
}
</script>