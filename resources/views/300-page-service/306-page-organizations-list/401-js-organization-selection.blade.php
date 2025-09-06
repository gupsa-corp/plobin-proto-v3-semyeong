{{-- 조직 선택 컴포넌트 (dashboardSidebar 확장) --}}
<script>
/**
 * 조직 선택 화면을 위한 간단한 확장 컴포넌트 
 * 기본 조직 관리 기능은 dashboardSidebar에서 처리
 */
function organizationSelectionView() {
    return {
        init() {
            // dashboardSidebar 컴포넌트가 조직 로딩을 담당
            // 이 컴포넌트는 단순히 조직 선택 화면 표시만 담당
            console.log('조직 선택 화면 초기화');
        },

        // 조직 선택 (dashboardSidebar의 selectOrganization 활용)
        selectOrg(org) {
            // dashboardSidebar 컴포넌트의 함수 호출
            if (window.dashboardSidebar && typeof window.dashboardSidebar.selectOrganization === 'function') {
                window.dashboardSidebar.selectOrganization(org.id);
            } else {
                // 폴백: 직접 처리
                localStorage.setItem('selectedOrg', JSON.stringify(org));
                window.location.href = `/organizations/${org.id}/dashboard`;
            }
        }
    }
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('organizationSelectionView', organizationSelectionView);
});
</script>