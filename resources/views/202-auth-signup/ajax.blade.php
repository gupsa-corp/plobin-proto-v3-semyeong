{{-- 이메일 중복 확인 AJAX --}}
<script>
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
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
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
                'X-Requested-With': 'XMLHttpRequest'
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
        errorDiv.textContent = '이메일 확인 중 오류가 발생했습니다.';
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
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('회원가입이 완료되었습니다!');
            // 토큰이 있다면 저장
            if (result.data && result.data.token) {
                localStorage.setItem('auth_token', result.data.token);
            }
            // 대시보드로 리다이렉트
            window.location.href = '/dashboard';
        } else {
            // 에러 메시지 표시
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    const errorElement = document.getElementById(field + 'Error');
                    if (errorElement) {
                        errorElement.textContent = result.errors[field][0];
                        errorElement.classList.remove('hidden');
                    }
                });
            } else if (result.message) {
                alert(result.message);
            }
        }
    } catch (error) {
        console.error('회원가입 오류:', error);
        alert('회원가입 중 오류가 발생했습니다. 다시 시도해주세요.');
    }
}
</script>