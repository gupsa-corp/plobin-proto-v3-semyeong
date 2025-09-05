{{-- 최근 프로젝트 조회 AJAX 함수 --}}
<script>
/**
 * 최근 프로젝트를 로드합니다
 * @param {number} orgId - 조직 ID
 * @returns {Array} 프로젝트 목록
 */
async function loadRecentProjects(orgId) {
    try {
        console.log('최근 프로젝트 로드 시작:', orgId);

        const data = await ajaxGet(`/api/organizations/${orgId}/projects?limit=5`);
        console.log('최근 프로젝트 API 응답:', data);

        // 응답 데이터 구조에 따른 프로젝트 목록 추출
        let projects = [];
        if (data.success && data.data && Array.isArray(data.data)) {
            projects = data.data;
        } else if (data.projects && Array.isArray(data.projects)) {
            projects = data.projects;
        } else if (Array.isArray(data)) {
            projects = data;
        }

        return projects.length > 0 ? projects : getDefaultProjects();

    } catch (error) {
        console.error('최근 프로젝트 로드 실패:', error.message);
        // API 에러 시 기본값 반환
        return getDefaultProjects();
    }
}
</script>