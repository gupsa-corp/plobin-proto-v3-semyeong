{{-- 이메일 중복확인 기능 --}}
<script>
// 이메일 입력 시 상태 초기화
function setupEmailInputHandler() {
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.addEventListener('input', () => {
            emailChecked = false;
            emailAvailable = false;
            document.getElementById('emailStatus').classList.add('hidden');
            document.getElementById('emailError').classList.add('hidden');
        });
    }
}

// 중복확인 버튼 클릭 이벤트 설정
function setupEmailCheckButton() {
    const checkEmailBtn = document.getElementById('checkEmailBtn');
    if (checkEmailBtn) {
        checkEmailBtn.addEventListener('click', checkEmail);
    }
}
</script>