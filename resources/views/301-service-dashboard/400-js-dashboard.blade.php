{{-- 대시보드 Alpine.js 컴포넌트 (공통 컴포넌트 활용) --}}
<script>
/**
 * 대시보드를 관리하는 Alpine.js 컴포넌트 (dashboardSidebar 확장)
 */
function dashboardMain() {
    return {
        // State
        projects: [],
        isLoading: false,

        init() {
            // 기본 인증과 조직 관리는 dashboardSidebar에서 처리
            this.initProjects();
        },

        // Project Management  
        async initProjects() {
            const selectedOrg = localStorage.getItem('selectedOrg');
            if (selectedOrg) {
                const orgData = JSON.parse(selectedOrg);
                this.loadProjects(orgData.id);
            }
        },

        async loadProjects(orgId) {
            try {
                this.isLoading = true;
                // TODO: 실제 API 구현 시 ApiClient 사용
                // const data = await ApiClient.get(`/api/organizations/${orgId}/projects`);
                // this.projects = data.data?.projects || [];
                
                // 임시 처리
                await new Promise(resolve => setTimeout(resolve, 500));
                this.projects = [];
            } catch (error) {
                ApiErrorHandler.handle(error, '프로젝트 로드');
                this.projects = [];
            } finally {
                this.isLoading = false;
            }
        },

        // Utilities
        get hasProjects() {
            return this.projects.length > 0;
        }
    }
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardMain', dashboardMain);
});
</script>