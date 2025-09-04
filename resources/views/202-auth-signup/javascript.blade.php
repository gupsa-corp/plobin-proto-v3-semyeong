{{-- 회원가입 페이지 JavaScript 로직 --}}
<script>
let emailChecked = false;
let emailAvailable = false;

// 이메일 입력 시 상태 초기화
document.getElementById('email').addEventListener('input', () => {
    emailChecked = false;
    emailAvailable = false;
    document.getElementById('emailStatus').classList.add('hidden');
    document.getElementById('emailError').classList.add('hidden');
});

// 중복확인 버튼 클릭
document.getElementById('checkEmailBtn').addEventListener('click', checkEmail);

// 폼 제출
document.getElementById('signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // 에러 메시지 초기화
    document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
    
    // 이메일 확인 여부 체크
    if (!emailChecked) {
        document.getElementById('emailError').textContent = '이메일 중복확인을 해주세요.';
        document.getElementById('emailError').classList.remove('hidden');
        return;
    }
    
    if (!emailAvailable) {
        document.getElementById('emailError').textContent = '사용할 수 없는 이메일입니다.';
        document.getElementById('emailError').classList.remove('hidden');
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = '회원가입 중...';
    
    const formData = new FormData(e.target);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        password_confirmation: formData.get('password_confirmation')
    };
    
    try {
        await submitSignup(data);
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = '회원가입';
    }
});
</script>