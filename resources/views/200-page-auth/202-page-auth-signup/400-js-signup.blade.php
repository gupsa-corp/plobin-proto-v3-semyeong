{{-- 회원가입 페이지 JavaScript 로직 --}}
<script>
let emailChecked = false;
let emailAvailable = false;

// 실시간 유효성 검사
function setupRealTimeValidation() {
    // 이름 필드 검증
    document.getElementById('name').addEventListener('blur', function() {
        const name = this.value.trim();
        const errorElement = document.getElementById('nameError');
        
        if (!name || name.length < 2) {
            errorElement.textContent = '이름은 2글자 이상 입력해주세요.';
            errorElement.classList.remove('hidden');
        } else if (!/^[가-힣a-zA-Z\s]+$/.test(name)) {
            errorElement.textContent = '이름은 한글, 영문만 입력 가능합니다.';
            errorElement.classList.remove('hidden');
        } else {
            errorElement.classList.add('hidden');
        }
    });
    
    // 비밀번호 필드 검증
    document.getElementById('password').addEventListener('blur', function() {
        const password = this.value;
        const errorElement = document.getElementById('passwordError');
        
        if (!password || password.length < 8) {
            errorElement.textContent = '비밀번호는 최소 8자 이상이어야 합니다.';
            errorElement.classList.remove('hidden');
        } else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(password)) {
            errorElement.textContent = '비밀번호는 대소문자, 숫자, 특수문자를 포함해야 합니다.';
            errorElement.classList.remove('hidden');
        } else {
            errorElement.classList.add('hidden');
        }
    });
    
    // 비밀번호 확인 필드 검증
    document.getElementById('password_confirmation').addEventListener('blur', function() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = this.value;
        const errorElement = document.getElementById('password_confirmationError');
        
        if (!passwordConfirmation || password !== passwordConfirmation) {
            errorElement.textContent = '비밀번호가 일치하지 않습니다.';
            errorElement.classList.remove('hidden');
        } else {
            errorElement.classList.add('hidden');
        }
    });
}

// 이메일 입력 시 상태 초기화
document.getElementById('email').addEventListener('input', () => {
    emailChecked = false;
    emailAvailable = false;
    document.getElementById('emailStatus').classList.add('hidden');
    document.getElementById('emailError').classList.add('hidden');
});

// 중복확인 버튼 클릭
document.getElementById('checkEmailBtn').addEventListener('click', checkEmail);

// 페이지 로드 시 실시간 검증 설정
document.addEventListener('DOMContentLoaded', setupRealTimeValidation);

// 클라이언트 사이드 유효성 검사 함수
function validateForm(data) {
    const errors = {};
    
    // 이름 검증
    if (!data.name || data.name.trim().length < 2) {
        errors.name = '이름은 2글자 이상 입력해주세요.';
    } else if (!/^[가-힣a-zA-Z\s]+$/.test(data.name.trim())) {
        errors.name = '이름은 한글, 영문만 입력 가능합니다.';
    }
    
    // 이메일 검증
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!data.email || !emailRegex.test(data.email.trim())) {
        errors.email = '올바른 이메일 형식을 입력해주세요.';
    }
    
    // 비밀번호 검증
    if (!data.password || data.password.length < 8) {
        errors.password = '비밀번호는 최소 8자 이상이어야 합니다.';
    } else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(data.password)) {
        errors.password = '비밀번호는 대소문자, 숫자, 특수문자를 포함해야 합니다.';
    }
    
    // 비밀번호 확인
    if (!data.password_confirmation || data.password !== data.password_confirmation) {
        errors.password_confirmation = '비밀번호가 일치하지 않습니다.';
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
    const firstInput = document.getElementById(firstErrorField);
    if (firstInput) {
        firstInput.focus();
    }
}

// 폼 제출
document.getElementById('signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // 에러 메시지 초기화
    document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
    
    const formData = new FormData(e.target);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        password_confirmation: formData.get('password_confirmation')
    };
    
    // 클라이언트 사이드 유효성 검사
    const clientErrors = validateForm(data);
    if (Object.keys(clientErrors).length > 0) {
        displayErrors(clientErrors);
        return;
    }
    
    // 이메일 확인 여부 체크
    if (!emailChecked) {
        document.getElementById('emailError').textContent = '이메일 중복확인을 해주세요.';
        document.getElementById('emailError').classList.remove('hidden');
        document.getElementById('email').focus();
        return;
    }
    
    if (!emailAvailable) {
        document.getElementById('emailError').textContent = '사용할 수 없는 이메일입니다.';
        document.getElementById('emailError').classList.remove('hidden');
        document.getElementById('email').focus();
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = '회원가입 중...';
    
    try {
        await submitSignup(data);
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = '회원가입';
    }
});
</script>