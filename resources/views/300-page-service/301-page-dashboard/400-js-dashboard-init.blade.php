{{-- 대시보드 초기화 관리 --}}
<script>
/**
 * 대시보드 초기화를 담당하는 Alpine.js 컴포넌트
 * 기능: 페이지 로드 시 조직 상태를 확인하고 적절한 화면 표시
 */
function dashboardInit() {
    return {
        init() {
            // 조직 상태 확인 후 적절한 화면 표시
            this.checkOrganizationStatus();
        },

        checkOrganizationStatus() {
            const selectedOrg = localStorage.getItem('selectedOrg');
            
            if (!selectedOrg) {
                // 조직이 없으면 조직 선택 화면 표시
                window.organizationStatus?.showOrganizationSelection();
            } else {
                // 조직이 있으면 대시보드 표시
                const orgData = JSON.parse(selectedOrg);
                window.organizationStatus?.showDashboard(orgData);
            }
        }
    };
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardInit', dashboardInit);
});
</script>