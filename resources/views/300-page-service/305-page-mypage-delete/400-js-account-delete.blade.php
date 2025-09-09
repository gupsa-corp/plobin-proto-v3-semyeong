<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('accountDeletePage', () => ({
        init() {
            // 초기화 로직
        }
    }));
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('account-delete-form');
    const deleteReasonSelect = document.getElementById('delete-reason');
    const otherReasonSection = document.getElementById('other-reason-section');
    const passwordConfirm = document.getElementById('password-confirm');
    const confirmUnderstand = document.getElementById('confirm-understand');
    const confirmFinal = document.getElementById('confirm-final');
    const deleteConfirmationText = document.getElementById('delete-confirmation-text');
    const submitBtn = document.getElementById('delete-submit-btn');

    // 탈퇴 사유 선택 시 기타 입력란 표시/숨김
    deleteReasonSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherReasonSection.classList.remove('hidden');
            document.getElementById('other-reason').required = true;
        } else {
            otherReasonSection.classList.add('hidden');
            document.getElementById('other-reason').required = false;
        }
        validateForm();
    });

    // 폼 유효성 검사
    function validateForm() {
        const isReasonSelected = deleteReasonSelect.value !== '';
        const isPasswordEntered = passwordConfirm.value.trim() !== '';
        const isUnderstandChecked = confirmUnderstand.checked;
        const isFinalChecked = confirmFinal.checked;
        const isConfirmationTextCorrect = deleteConfirmationText.value.trim() === '계정삭제';

        let isOtherReasonValid = true;
        if (deleteReasonSelect.value === 'other') {
            const otherReasonText = document.getElementById('other-reason').value.trim();
            isOtherReasonValid = otherReasonText !== '';
        }

        const isValid = isReasonSelected && isPasswordEntered && isUnderstandChecked &&
                        isFinalChecked && isConfirmationTextCorrect && isOtherReasonValid;

        if (isValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('hover:bg-red-700');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('hover:bg-red-700');
        }
    }

    // 실시간 유효성 검사
    [deleteReasonSelect, passwordConfirm, confirmUnderstand, confirmFinal, deleteConfirmationText].forEach(element => {
        element.addEventListener('input', validateForm);
        element.addEventListener('change', validateForm);
    });

    document.getElementById('other-reason').addEventListener('input', validateForm);

    // 폼 제출 처리
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (submitBtn.disabled) return;

        // 최종 확인 대화상자
        const finalConfirm = confirm(
            '정말로 계정을 삭제하시겠습니까?\n\n' +
            '이 작업은 되돌릴 수 없으며, 모든 데이터가 영구적으로 삭제됩니다.\n\n' +
            '"확인"을 클릭하면 즉시 계정이 삭제됩니다.'
        );

        if (!finalConfirm) return;

        // 추가 확인 대화상자
        const doubleConfirm = confirm(
            '마지막 확인입니다.\n\n' +
            '계정 삭제를 진행하시겠습니까?'
        );

        if (!doubleConfirm) return;

        const formData = new FormData(this);

        // 버튼 비활성화 및 로딩 상태
        submitBtn.disabled = true;
        submitBtn.textContent = '삭제 처리중...';

        // 실제 계정 삭제 API 호출
        fetch('/mypage/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reason: formData.get('reason'),
                other_reason: formData.get('other_reason'),
                password: formData.get('password'),
                confirmation: formData.get('confirmation')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || '계정이 성공적으로 삭제되었습니다.\n지금까지 서비스를 이용해주셔서 감사합니다.');
                // 메인 페이지로 리다이렉트
                window.location.href = data.redirect || '/';
            } else {
                // 오류 처리
                alert(data.message || '회원 탈퇴 처리 중 오류가 발생했습니다.');
                
                // 조직 소유자인 경우 추가 정보 표시
                if (data.organizations && data.organizations.length > 0) {
                    const orgList = data.organizations.join(', ');
                    alert(`소유하고 있는 조직: ${orgList}\n\n조직을 먼저 삭제하거나 다른 사용자에게 양도한 후 탈퇴해주세요.`);
                }
                
                // 버튼 상태 복구
                submitBtn.disabled = false;
                submitBtn.textContent = '계정 영구 삭제';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('네트워크 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
            
            // 버튼 상태 복구
            submitBtn.disabled = false;
            submitBtn.textContent = '계정 영구 삭제';
        });
    });

    // 확인 텍스트 실시간 검증
    deleteConfirmationText.addEventListener('input', function() {
        const inputValue = this.value.trim();
        const isCorrect = inputValue === '계정삭제';

        if (inputValue && !isCorrect) {
            this.setCustomValidity('정확히 "계정삭제"를 입력해주세요.');
        } else {
            this.setCustomValidity('');
        }

        validateForm();
    });

    // 페이지 이탈 경고
    let formChanged = false;
    [deleteReasonSelect, passwordConfirm, deleteConfirmationText].forEach(element => {
        element.addEventListener('input', function() {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});
</script>
