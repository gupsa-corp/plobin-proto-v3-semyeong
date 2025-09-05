<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profileEditPage', () => ({
        init() {
            this.loadProfile();
        },

        async loadProfile() {
            // 프로필 정보 로드 로직
        }
    }));
});

// 개인정보 수정 폼 제출
document.addEventListener('DOMContentLoaded', function() {
    const profileEditForm = document.getElementById('profile-edit-form');
    const passwordChangeForm = document.getElementById('password-change-form');

    // 개인정보 수정
    profileEditForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        // TODO: 개인정보 수정 AJAX 호출
        console.log('개인정보 수정 요청', Object.fromEntries(formData));

        // 성공 시 프로필 페이지로 리다이렉트
        // window.location.href = '/profile';
    });

    // 비밀번호 변경
    passwordChangeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        // 비밀번호 확인 검증
        if (newPassword !== confirmPassword) {
            alert('새 비밀번호와 비밀번호 확인이 일치하지 않습니다.');
            return;
        }

        // 비밀번호 강도 검증 (최소 8자, 영문, 숫자, 특수문자)
        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(newPassword)) {
            alert('비밀번호는 최소 8자 이상, 영문, 숫자, 특수문자를 포함해야 합니다.');
            return;
        }

        const formData = new FormData(this);
        // TODO: 비밀번호 변경 AJAX 호출
        console.log('비밀번호 변경 요청');

        // 성공 시 폼 초기화
        // this.reset();
        // alert('비밀번호가 성공적으로 변경되었습니다.');
    });

    // 실시간 비밀번호 확인 검증
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    function validatePasswordMatch() {
        if (confirmPasswordInput.value && newPasswordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('비밀번호가 일치하지 않습니다.');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    newPasswordInput.addEventListener('input', validatePasswordMatch);
    confirmPasswordInput.addEventListener('input', validatePasswordMatch);
});
</script>
