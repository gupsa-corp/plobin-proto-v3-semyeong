{{-- 조직 멤버 목록 조회 AJAX 함수 --}}
<script>
/**
 * 조직 멤버 목록을 로드합니다
 * @param {number} orgId - 조직 ID
 * @returns {Array} 멤버 목록
 */
async function loadMembers(orgId) {
    try {
        const data = await ajaxGet(`/api/organizations/${orgId}/members`);

        // 응답 데이터 구조에 따른 멤버 목록 추출
        let members = [];
        if (data.success && data.data && Array.isArray(data.data)) {
            members = data.data;
        } else if (data.members && Array.isArray(data.members)) {
            members = data.members;
        } else if (Array.isArray(data)) {
            members = data;
        }

        return members;

    } catch (error) {
        console.error('멤버 목록 로드 실패:', error.message);
        ApiErrorHandler.handle(error, '멤버 목록 로드');
        return [];
    }
}
</script>