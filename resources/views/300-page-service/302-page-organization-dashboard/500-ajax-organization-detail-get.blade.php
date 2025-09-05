{{-- 조직 상세 정보 조회 AJAX 함수 --}}
<script>
/**
 * 조직 상세 정보를 로드합니다
 * @param {number} orgId - 조직 ID
 * @returns {Object|null} 조직 데이터
 */
async function loadOrganizationDetail(orgId) {
    try {
        console.log('조직 상세 정보 로드 시작:', orgId);

        const data = await ajaxGet(`/api/organizations/${orgId}`);
        console.log('조직 상세 정보 API 응답:', data);

        // 응답 데이터 구조에 따른 조직 정보 추출
        let orgData = null;
        if (data.success && data.data) {
            orgData = data.data;
        } else if (data.organization) {
            orgData = data.organization;
        } else if (data.name) {
            orgData = data;
        }

        return orgData;

    } catch (error) {
        console.error('조직 상세 정보 로드 실패:', error.message);
        ApiErrorHandler.handle(error, '조직 상세 정보 로드');
        return null;
    }
}
</script>