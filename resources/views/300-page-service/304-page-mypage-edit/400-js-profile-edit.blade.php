<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profileEditPage', () => ({
        loading: false,
        profileLoading: false,
        
        init() {
            this.loadProfile();
        },

        async loadProfile() {
            this.profileLoading = true;
            try {
                const response = await fetch('/api/auth/me', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success && result.data) {
                        // 폼에 사용자 정보 설정
                        document.getElementById('name').value = result.data.name || '';
                        document.getElementById('email').value = result.data.email || '';
                        document.getElementById('phone').value = result.data.phone || '';
                    }
                }
            } catch (error) {
                console.error('프로필 로드 오류:', error);
            } finally {
                this.profileLoading = false;
            }
        }
    }));
});

// 개인정보 수정 폼 제출
document.addEventListener('DOMContentLoaded', function() {
    const profileEditForm = document.getElementById('profile-edit-form');
    const passwordChangeForm = document.getElementById('password-change-form');

    // 개인정보 수정
    profileEditForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = '저장 중...';

        try {
            const formData = new FormData(this);
            const response = await fetch('/api/user/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    name: formData.get('name'),
                    phone: formData.get('phone')
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('프로필이 성공적으로 업데이트되었습니다.');
                window.location.href = '/mypage';
            } else {
                // 오류 메시지 표시
                let errorMessage = result.message || '프로필 업데이트에 실패했습니다.';
                if (result.errors) {
                    const errors = Object.values(result.errors).flat();
                    errorMessage = errors.join('\n');
                }
                alert(errorMessage);
            }
        } catch (error) {
            console.error('프로필 수정 오류:', error);
            alert('프로필 업데이트 중 오류가 발생했습니다.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });

    // 비밀번호 변경
    passwordChangeForm.addEventListener('submit', async function(e) {
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

        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = '변경 중...';

        try {
            const formData = new FormData(this);
            const response = await fetch('/api/user/password', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    current_password: formData.get('current_password'),
                    new_password: formData.get('new_password'),
                    new_password_confirmation: formData.get('new_password_confirmation')
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('비밀번호가 성공적으로 변경되었습니다.');
                this.reset();
            } else {
                // 오류 메시지 표시
                let errorMessage = result.message || '비밀번호 변경에 실패했습니다.';
                if (result.errors) {
                    const errors = Object.values(result.errors).flat();
                    errorMessage = errors.join('\n');
                }
                alert(errorMessage);
            }
        } catch (error) {
            console.error('비밀번호 변경 오류:', error);
            alert('비밀번호 변경 중 오류가 발생했습니다.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
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
