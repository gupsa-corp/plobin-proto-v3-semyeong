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

        // Create Modal Management
        showCreateModal() {
            this.isCreateModalOpen = true;
            this.resetForm();
            this.$nextTick(() => {
                this.$refs.orgNameInput?.focus();
            });
        },

        closeCreateModal() {
            this.isCreateModalOpen = false;
            this.resetForm();
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

                const response = await fetch('/api/organizations/create', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.getAuthToken()}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.newOrgName,
                        url_path: this.newOrgUrl
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.createdOrg = data.data;
                    this.closeCreateModal();
                    this.showSuccessModal();
                } else {
                    this.formErrors = data.errors || { general: [data.message] };
                }
            } catch (error) {
                console.error('조직 생성 오류:', error);
                this.formErrors = { general: ['네트워크 오류가 발생했습니다.'] };
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

        // Success Modal Management
        showSuccessModal() {
            this.isSuccessModalOpen = true;
        },

        closeSuccessModal() {
            this.isSuccessModalOpen = false;
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