{{-- 닉네임 검증 기능 --}}
<script>
// 닉네임 필드 검증 설정
function setupNicknameValidation() {
    const nicknameField = document.getElementById('nickname');
    if (nicknameField) {
        nicknameField.addEventListener('blur', function() {
            const nickname = this.value.trim();
            const errorElement = document.getElementById('nicknameError');
            
            if (errorElement) {
                if (nickname && (nickname.length < 2 || nickname.length > 20)) {
                    errorElement.textContent = '닉네임은 2-20글자로 입력해주세요.';
                    errorElement.classList.remove('hidden');
                } else if (nickname && !/^[가-힣a-zA-Z0-9_-]+$/.test(nickname)) {
                    errorElement.textContent = '닉네임은 한글, 영문, 숫자, _, - 만 사용 가능합니다.';
                    errorElement.classList.remove('hidden');
                } else {
                    errorElement.classList.add('hidden');
                }
            }
        });
    }
}
</script>