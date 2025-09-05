{{-- 조직 모달 Alpine.js 컴포넌트 (경량화 버전) --}}
<script>
/**
 * 조직 생성/관리 모달을 관리하는 Alpine.js 컴포넌트 (commons-javascript 기반)
 */
function organizationModal() {
    return {
        // State
        isCreateModalOpen: false,
        isSuccessModalOpen: false,
        isManagerModalOpen: false,
        isLoading: false,
        
        // Form data
        newOrgName: '',
        newOrgUrl: '',
        formErrors: {},
        
        // Success data
        createdOrg: null,

        // Create Modal Management (ModalUtils 활용)
        showCreateModal() {
            this.isCreateModalOpen = true;
            this.resetForm();
            ModalUtils.showModal('createOrgModal');
            this.$nextTick(() => {
                this.$refs.orgNameInput?.focus();
            });
        },

        closeCreateModal() {
            this.isCreateModalOpen = false;
            this.resetForm();
            ModalUtils.hideModal('createOrgModal');
        },

        resetForm() {
            this.newOrgName = '';
            this.newOrgUrl = '';
            this.formErrors = {};
        },

        // Form Handling
        handleUrlInput() {
            this.newOrgUrl = this.newOrgUrl.replace(/[^a-zA-Z]/g, '');
        },

        async createOrganization() {
            if (!this.validateForm()) return;

            try {
                this.isLoading = true;
                this.formErrors = {};

                const data = await ApiClient.post('/api/organizations/create', {
                    name: this.newOrgName,
                    url_path: this.newOrgUrl
                });

                this.createdOrg = data.data;
                this.closeCreateModal();
                this.showSuccessModal();
            } catch (error) {
                ApiErrorHandler.handle(error, '조직 생성');
                // API 클라이언트에서 이미 422 에러를 처리하므로 기본 메시지만 표시
                this.formErrors = { general: ['조직 생성에 실패했습니다.'] };
            } finally {
                this.isLoading = false;
            }
        },

        validateForm() {
            if (!this.newOrgName.trim() || !this.newOrgUrl.trim()) {
                this.formErrors = { general: ['모든 필드를 입력해주세요.'] };
                return false;
            }
            return true;
        },

        // Success Modal Management (ModalUtils 활용)
        showSuccessModal() {
            this.isSuccessModalOpen = true;
            ModalUtils.showModal('successModal');
        },

        closeSuccessModal() {
            this.isSuccessModalOpen = false;
            ModalUtils.hideModal('successModal');
        },

        goToOrganization() {
            if (this.createdOrg) {
                localStorage.setItem('selectedOrg', JSON.stringify(this.createdOrg));
                window.location.href = `/organizations/${this.createdOrg.id}/dashboard`;
            }
        },

        // Manager Modal Management
        showManagerModal() {
            this.isManagerModalOpen = true;
        },

        closeManagerModal() {
            this.isManagerModalOpen = false;
        },

        // Utilities
        getAuthToken() {
            const token = document.querySelector('meta[name="auth-token"]')?.content;
            return token || localStorage.getItem('auth_token') || '';
        },

        getFieldError(field) {
            return this.formErrors[field]?.[0] || '';
        },

        hasFieldError(field) {
            return !!this.formErrors[field];
        },

        get generalError() {
            return this.formErrors.general?.[0] || '';
        },

        get hasGeneralError() {
            return !!this.formErrors.general;
        }
    }
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('organizationModal', organizationModal);
});
</script>