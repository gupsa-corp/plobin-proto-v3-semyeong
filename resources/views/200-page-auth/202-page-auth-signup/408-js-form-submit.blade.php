{{-- 폼 제출 처리 기능 --}}
<script>
// 폼 제출 이벤트 설정
function setupFormSubmit() {
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // 에러 메시지 초기화
            document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
            
            const formData = new FormData(e.target);
            const firstName = formData.get('first_name') || '';
            const lastName = formData.get('last_name') || '';
            const data = {
                email: formData.get('email'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation'),
                country_code: formData.get('country_code'),
                phone_number: formData.get('phone_number'),
                nickname: formData.get('nickname'),
                name: (firstName + ' ' + lastName).trim(), // Combine first_name and last_name into name
                first_name: firstName,
                last_name: lastName
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
    }
}
</script>