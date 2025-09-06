{{-- 조직 생성 AJAX 함수 --}}
<script>
/**
 * 조직 생성 API 호출 함수
 */
async function createOrganization() {
    if (!this.validateForm()) return;

    try {
        this.isLoading = true;
        this.formErrors = {};

        const data = await ajaxPost('/api/organizations/create', {
            name: this.newOrgName
        });

        this.createdOrg = data.data;
        this.closeCreateModal();
        this.showSuccessModal();
        
        // 조직 목록 새로고침
        if (typeof window.loadOrganizations === 'function') {
            setTimeout(() => {
                window.loadOrganizations();
            }, 500);
        }
    } catch (error) {
        ApiErrorHandler.handle(error, '조직 생성');
        // API 클라이언트에서 이미 422 에러를 처리하므로 기본 메시지만 표시
        this.formErrors = { general: ['조직 생성에 실패했습니다.'] };
    } finally {
        this.isLoading = false;
    }
}
</script>