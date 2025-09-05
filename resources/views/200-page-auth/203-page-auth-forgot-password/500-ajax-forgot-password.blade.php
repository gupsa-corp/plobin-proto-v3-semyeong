<script>
// 비밀번호 찾기 AJAX 처리 스크립트
document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const loadingMessage = document.getElementById('loadingMessage');
    const errorText = document.getElementById('errorText');
    const successText = document.getElementById('successText');

    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // 메시지 숨기기
            hideMessages();
            
            // 폼 데이터 수집
            const formData = new FormData(forgotPasswordForm);
            const email = formData.get('email');
            
            // 기본 유효성 검사
            if (!email) {
                showError('이메일 주소를 입력해주세요.');
                return;
            }
            
            // 이메일 형식 검사
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
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
                전송 중...
            `;
            
            // 로딩 메시지 표시
            showLoading();
            
            try {
                // CSRF 토큰 가져오기
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;
                
                const response = await fetch('/api/auth/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // 이메일 전송 성공
                    const successMsg = data.message || '입력하신 이메일로 비밀번호 재설정 링크를 전송했습니다.\n이메일을 확인해 주세요.';
                    showSuccess(successMsg);
                    
                    // 폼 리셋
                    forgotPasswordForm.reset();
                    
                    // 5초 후 로그인 페이지로 이동하는 옵션 제공
                    setTimeout(() => {
                        const redirectConfirm = confirm('로그인 페이지로 돌아가시겠습니까?');
                        if (redirectConfirm) {
                            window.location.href = '/login';
                        }
                    }, 5000);
                    
                } else {
                    // 이메일 전송 실패
                    let errorMsg = '';
                    
                    if (data.errors) {
                        // Laravel validation errors
                        if (data.errors.email) {
                            errorMsg = data.errors.email[0];
                        } else {
                            errorMsg = Object.values(data.errors)[0][0] || '입력 정보를 확인해주세요.';
                        }
                    } else {
                        errorMsg = data.message || '이메일 전송에 실패했습니다. 입력하신 이메일 주소를 다시 확인해주세요.';
                    }
                    
                    showError(errorMsg);
                }
                
            } catch (error) {
                console.error('비밀번호 찾기 AJAX 요청 중 오류:', error);
                
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </span>
                    비밀번호 재설정 링크 전송
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
        
        // 메시지 영역으로 스크롤
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function showSuccess(message) {
        successText.innerHTML = message.replace(/\n/g, '<br>');
        successMessage.classList.remove('hidden');
        successMessage.classList.add('message-enter');
        errorMessage.classList.add('hidden');
        
        // 메시지 영역으로 스크롤
        successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function showLoading() {
        loadingMessage.classList.remove('hidden');
        loadingMessage.classList.add('message-enter');
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
    }
    
    function hideLoading() {
        loadingMessage.classList.add('hidden');
    }
    
    function hideMessages() {
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
        loadingMessage.classList.add('hidden');
    }
    
    // 이메일 입력 필드 포커스 시 에러 메시지 숨기기
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('focus', hideMessages);
        
        // Enter 키 처리
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                forgotPasswordForm.requestSubmit();
            }
        });
        
        // 실시간 이메일 형식 검증
        emailInput.addEventListener('input', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            // 입력값이 있고 이메일 형식이 아닐 때만 스타일 변경
            if (email && !emailRegex.test(email)) {
                e.target.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                e.target.classList.remove('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
            } else {
                e.target.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                e.target.classList.add('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
            }
        });
    }
    
    // 폼 리셋 시 메시지 숨기기
    forgotPasswordForm?.addEventListener('reset', hideMessages);
    
    console.log('비밀번호 찾기 AJAX 스크립트 로드 완료');
});

// AJAX 응답 상태 코드별 처리
function handleForgotPasswordAjaxError(xhr, status, error) {
    console.error('Forgot Password AJAX Error:', { xhr, status, error });
    
    let message = '알 수 없는 오류가 발생했습니다.';
    
    if (xhr.status === 400) {
        message = '잘못된 요청입니다. 입력 정보를 확인해주세요.';
    } else if (xhr.status === 404) {
        message = '입력하신 이메일 주소로 등록된 계정을 찾을 수 없습니다.';
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