{{-- 프로젝트 관리 --}}
<script>
/**
 * 프로젝트 로드 및 관리를 담당하는 함수
 * 기능: 프로젝트 목록 로드, 프로젝트 상태 관리
 */
function projectManager() {
    return {
        projects: [],
        isLoading: false,

        async loadProjects(orgId) {
            try {
                this.isLoading = true;
                // TODO: 실제 API 구현 시 ApiClient 사용
                // const data = await ApiClient.get(`/api/organizations/${orgId}/projects`);
                // this.projects = data.data?.projects || [];
                
                // 임시 처리
                await new Promise(resolve => setTimeout(resolve, 500));
                this.projects = [];
                
                console.log(`조직 ${orgId}의 프로젝트 목록을 로드했습니다.`, this.projects);
            } catch (error) {
                console.error('프로젝트 로드 실패:', error);
                if (window.ApiErrorHandler) {
                    window.ApiErrorHandler.handle(error, '프로젝트 로드');
                }
                this.projects = [];
            } finally {
                this.isLoading = false;
            }
        },

        get hasProjects() {
            return this.projects.length > 0;
        }
    };
}

// 전역 객체로 등록
document.addEventListener('DOMContentLoaded', () => {
    window.projectManager = projectManager();
});
</script>