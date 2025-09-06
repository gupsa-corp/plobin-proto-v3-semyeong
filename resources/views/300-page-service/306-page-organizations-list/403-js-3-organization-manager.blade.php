{{-- 조직 관리자 --}}
<script>
/**
 * 조직 관련 비즈니스 로직을 담당하는 클래스
 * 기능: 조직 생성, 유효성 검증, 상태 관리
 */
class OrganizationManager {
    constructor(apiClient, modalUI) {
        this.api = apiClient;
        this.modal = modalUI;
        this.isProcessing = false;
    }

    // 조직 이름 유효성 검증
    validateOrganizationName(name) {
        if (!name || typeof name !== 'string') {
            return { isValid: false, message: '조직 이름을 입력해주세요.' };
        }

        const trimmedName = name.trim();

        if (trimmedName.length < 1) {
            return { isValid: false, message: '조직 이름을 입력해주세요.' };
        }

        if (trimmedName.length > 25) {
            return { isValid: false, message: '조직 이름은 25자 이하여야 합니다.' };
        }

        return { isValid: true, name: trimmedName };
    }

    // UI 요소 가져오기
    getUIElements() {
        return {
            nameInput: document.getElementById('orgName'),
            submitBtn: document.getElementById('createOrgSubmitBtn')
        };
    }

    // 버튼 상태 업데이트
    updateSubmitButton(isLoading = false) {
        const { submitBtn } = this.getUIElements();

        if (!submitBtn) return;

        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.textContent = '생성 중...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = false;
            submitBtn.textContent = '생성하기';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // 폼 초기화
    resetForm() {
        const { nameInput } = this.getUIElements();
        if (nameInput) {
            nameInput.value = '';
        }
    }

    // 성공 처리
    handleSuccess(message = '조직이 성공적으로 생성되었습니다!') {
        alert(message);
        this.modal.hideCreateOrganizationModal();
        this.resetForm();

        // 페이지 새로고침하여 조직 목록 업데이트
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }

    // 에러 처리
    handleError(error) {
        console.error('조직 생성 오류:', error);
        const message = error.message || '조직 생성에 실패했습니다.';
        alert(`조직 생성에 실패했습니다: ${message}`);
    }

    // 조직 생성 처리
    async createOrganization() {
        // 중복 처리 방지
        if (this.isProcessing) {
            console.log('이미 처리 중입니다.');
            return;
        }

        const { nameInput } = this.getUIElements();

        if (!nameInput) {
            console.error('조직 이름 입력 필드를 찾을 수 없습니다.');
            return;
        }

        // 유효성 검증
        const validation = this.validateOrganizationName(nameInput.value);
        if (!validation.isValid) {
            alert(validation.message);
            nameInput.focus();
            return;
        }

        this.isProcessing = true;
        this.updateSubmitButton(true);

        try {
            // 기존 ajaxPost 함수 사용 (Bearer 토큰 자동 처리)
            const result = await ajaxPost('/api/organizations/create', {
                name: validation.name
            });

            // ajaxPost는 성공시 data를 리턴함
            this.handleSuccess();

        } catch (error) {
            this.handleError(error);
        } finally {
            this.isProcessing = false;
            this.updateSubmitButton(false);
        }
    }

    // 조직 목록 새로고침 (필요시)
    async refreshOrganizationList() {
        try {
            const result = await this.api.get('/organizations');
            // 조직 목록 UI 업데이트 로직
            console.log('조직 목록:', result);
        } catch (error) {
            console.error('조직 목록 조회 실패:', error);
        }
    }
}

// 전역 조직 관리자 인스턴스
document.addEventListener('DOMContentLoaded', () => {
    window.organizationManager = new OrganizationManager(window.apiClient, window.modalUI);
});
</script>
