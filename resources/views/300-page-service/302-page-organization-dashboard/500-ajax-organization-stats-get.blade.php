{{-- 대시보드 통계 조회 AJAX 함수 --}}
<script>
/**
 * 대시보드 통계를 로드합니다
 * @param {number} orgId - 조직 ID
 * @returns {Object|null} 통계 데이터
 */
async function loadDashboardStats(orgId) {
    try {
        console.log('대시보드 통계 로드 시작:', orgId);

        const data = await ajaxGet(`/api/organizations/${orgId}/stats`);
        console.log('대시보드 통계 API 응답:', data);

        // 응답 데이터 구조에 따른 통계 정보 추출
        let stats = null;
        if (data.success && data.data) {
            stats = data.data;
        } else if (data.stats) {
            stats = data.stats;
        } else {
            stats = data;
        }

        return stats || getDefaultStats();

    } catch (error) {
        console.error('대시보드 통계 로드 실패:', error.message);
        // API 에러 시 기본값 반환
        return getDefaultStats();
    }
}
</script>