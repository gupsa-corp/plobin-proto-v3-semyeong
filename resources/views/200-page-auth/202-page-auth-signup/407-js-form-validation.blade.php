{{-- 폼 유효성 검사 및 에러 처리 기능 --}}
<script>
// 클라이언트 사이드 유효성 검사 함수
function validateForm(data) {
    const errors = {};
    
    // 이름 검증
    if (!data.name || data.name.trim().length < 2) {
        errors.firstName = '이름을 입력해주세요.';
    }
    
    // 이메일 검증
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!data.email || !emailRegex.test(data.email.trim())) {
        errors.email = '올바른 이메일 형식을 입력해주세요.';
    }
    
    // 비밀번호 검증
    if (!data.password || data.password.length < 8) {
        errors.password = '비밀번호는 최소 8자 이상이어야 합니다.';
    } else if (!/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(data.password)) {
        errors.password = '비밀번호는 소문자, 숫자, 특수문자를 포함해야 합니다.';
    }
    
    // 비밀번호 확인
    if (!data.password_confirmation || data.password !== data.password_confirmation) {
        errors.passwordConfirmation = '비밀번호가 일치하지 않습니다.';
    }
    
    return errors;
}

// 에러 메시지 표시 함수
function displayErrors(errors) {
    Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
            errorElement.textContent = errors[field];
            errorElement.classList.remove('hidden');
        }
    });
    
    // 첫 번째 에러 필드로 포커스 이동
    const firstErrorField = Object.keys(errors)[0];
    // passwordConfirmation 필드는 실제 input ID가 password_confirmation이므로 매핑
    const inputId = firstErrorField === 'passwordConfirmation' ? 'password_confirmation' : firstErrorField;
    const firstInput = document.getElementById(inputId);
    if (firstInput) {
        firstInput.focus();
    }
}
</script>