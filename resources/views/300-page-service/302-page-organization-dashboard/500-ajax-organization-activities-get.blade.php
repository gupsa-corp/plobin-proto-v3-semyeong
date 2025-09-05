{{-- 최근 활동 조회 AJAX 함수 --}}
<script>
/**
 * 최근 활동을 로드합니다
 * @param {number} orgId - 조직 ID
 * @returns {Array} 활동 목록
 */
async function loadRecentActivities(orgId) {
    try {
        console.log('최근 활동 로드 시작:', orgId);

        const data = await ajaxGet(`/api/organizations/${orgId}/activities?limit=5`);
        console.log('최근 활동 API 응답:', data);

        // 응답 데이터 구조에 따른 활동 목록 추출
        let activities = [];
        if (data.success && data.data && Array.isArray(data.data)) {
            activities = data.data;
        } else if (data.activities && Array.isArray(data.activities)) {
            activities = data.activities;
        } else if (Array.isArray(data)) {
            activities = data;
        }

        return activities.length > 0 ? activities : getDefaultActivities();

    } catch (error) {
        console.error('최근 활동 로드 실패:', error.message);
        // API 에러 시 기본값 반환
        return getDefaultActivities();
    }
}
</script>