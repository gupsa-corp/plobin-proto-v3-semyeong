{{-- 조직 상태 관리 --}}
<script>
/**
 * 조직 선택 화면과 대시보드 간 전환을 관리하는 함수
 * 기능: 조직 선택 화면 표시/숨김, 대시보드 화면 전환
 */
function organizationStatusManager() {
    return {
        // 조직 선택 화면 표시
        showOrganizationSelection() {
            const selectionScreen = document.getElementById('organizationSelectionScreen');
            if (selectionScreen) {
                selectionScreen.style.display = 'block';
            }
            
            // 조직 목록 로드
            window.organizationList?.loadOrganizations();
        },

        // 대시보드 표시 
        showDashboard(orgData) {
            // 조직 선택 화면 숨기기
            const selectionScreen = document.getElementById('organizationSelectionScreen');
            if (selectionScreen) {
                selectionScreen.style.display = 'none';
            }
            
            // 프로젝트 로드
            window.projectManager?.loadProjects(orgData.id);
        }
    };
}

// 전역 객체로 등록
document.addEventListener('DOMContentLoaded', () => {
    window.organizationStatus = organizationStatusManager();
});
</script>