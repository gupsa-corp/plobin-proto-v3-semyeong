{{-- 이메일 중복 확인 AJAX --}}
<script>
// 유틸리티 함수들
function showSuccessMessage(message) {
    // 성공 메시지를 표시하는 토스트 또는 알림
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function showErrorMessage(message) {
    // 에러 메시지를 표시하는 토스트 또는 알림
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// 이메일 중복 확인 함수
async function checkEmail() {
    const emailInput = document.getElementById('email');
    const checkBtn = document.getElementById('checkEmailBtn');
    const statusDiv = document.getElementById('emailStatus');
    const errorDiv = document.getElementById('emailError');
    
    const email = emailInput.value.trim();
    
    if (!email) {
        errorDiv.textContent = '이메일을 입력해주세요.';
        errorDiv.classList.remove('hidden');
        statusDiv.classList.add('hidden');
        return;
    }
    
    // 기본 이메일 형식 검증
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
        errorDiv.textContent = '올바른 이메일 형식을 입력해주세요.';
        errorDiv.classList.remove('hidden');
        statusDiv.classList.add('hidden');
        return;
    }
    
    // 에러 메시지 숨기기
    errorDiv.classList.add('hidden');
    
    checkBtn.disabled = true;
    checkBtn.textContent = '확인중...';
    
    try {
        const response = await fetch('/api/auth/check-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ email })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            emailChecked = true;
            emailAvailable = result.data.available;
            
            statusDiv.classList.remove('hidden');
            if (result.data.available) {
                statusDiv.textContent = '✓ 사용 가능한 이메일입니다.';
                statusDiv.className = 'text-sm mt-1 text-green-600';
            } else {
                statusDiv.textContent = '✗ 이미 사용중인 이메일입니다.';
                statusDiv.className = 'text-sm mt-1 text-red-600';
            }
        } else {
            if (result.errors && result.errors.email) {
                errorDiv.textContent = result.errors.email[0];
                errorDiv.classList.remove('hidden');
            } else {
                errorDiv.textContent = result.message || '이메일 확인 중 오류가 발생했습니다.';
                errorDiv.classList.remove('hidden');
            }
            statusDiv.classList.add('hidden');
        }
    } catch (error) {
        console.error('이메일 확인 오류:', error);
        errorDiv.textContent = '네트워크 오류가 발생했습니다. 인터넷 연결을 확인해주세요.';
        errorDiv.classList.remove('hidden');
        statusDiv.classList.add('hidden');
    } finally {
        checkBtn.disabled = false;
        checkBtn.textContent = '중복확인';
    }
}

// 회원가입 AJAX
async function submitSignup(data) {
    try {
        const response = await fetch('/api/auth/signup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // 토큰이 있다면 저장
            if (result.data && result.data.token) {
                setAuthToken(result.data.token);
            }
            
            // 서버에서 지정한 URL로 즉시 리다이렉트
            const redirectUrl = (result.data && result.data.redirect_url) || '/dashboard';
            window.location.href = redirectUrl;
        } else {
            // 에러 메시지 표시 개선
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    const errorElement = document.getElementById(field + 'Error');
                    if (errorElement) {
                        errorElement.textContent = Array.isArray(result.errors[field]) 
                            ? result.errors[field][0] 
                            : result.errors[field];
                        errorElement.classList.remove('hidden');
                    }
                });
                // 첫 번째 에러 필드로 포커스 이동
                const firstErrorField = Object.keys(result.errors)[0];
                const firstInput = document.getElementById(firstErrorField);
                if (firstInput) {
                    firstInput.focus();
                }
            } else if (result.message) {
                showErrorMessage(result.message);
            } else {
                showErrorMessage('회원가입 중 오류가 발생했습니다.');
            }
        }
    } catch (error) {
        console.error('회원가입 오류:', error);
        showErrorMessage('네트워크 오류가 발생했습니다. 인터넷 연결을 확인하고 다시 시도해주세요.');
    }
}
</script>