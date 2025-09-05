{{-- 비밀번호 검증 기능 --}}
<script>
// 비밀번호 필드 검증 설정
function setupPasswordValidation() {
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.addEventListener('blur', function() {
            const password = this.value;
            const errorElement = document.getElementById('passwordError');
            
            if (!password || password.length < 8) {
                errorElement.textContent = '비밀번호는 최소 8자 이상이어야 합니다.';
                errorElement.classList.remove('hidden');
            } else if (!/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(password)) {
                errorElement.textContent = '비밀번호는 소문자, 숫자, 특수문자를 포함해야 합니다.';
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        });
    }
}

// 비밀번호 확인 필드 검증 설정
function setupPasswordConfirmationValidation() {
    const passwordConfirmationField = document.getElementById('password_confirmation');
    if (passwordConfirmationField) {
        passwordConfirmationField.addEventListener('blur', function() {
            const password = document.getElementById('password').value;
            const passwordConfirmation = this.value;
            const errorElement = document.getElementById('passwordConfirmationError');
            
            if (!passwordConfirmation || password !== passwordConfirmation) {
                errorElement.textContent = '비밀번호가 일치하지 않습니다.';
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        });
    }
}
</script>