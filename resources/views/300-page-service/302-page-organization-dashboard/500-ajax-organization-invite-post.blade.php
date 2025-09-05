{{-- 조직 멤버 초대 AJAX 함수 --}}
<script>
/**
 * 멤버를 초대합니다
 * @param {number} orgId - 조직 ID
 * @param {string} email - 초대할 이메일
 * @param {string} role - 역할
 * @returns {boolean} 성공 여부
 */
async function inviteMember(orgId, email, role = 'member') {
    try {
        const data = await ajaxPost(`/api/organizations/${orgId}/invite`, { 
            email: email, 
            role: role 
        });
        
        return data.success || true;

    } catch (error) {
        console.error('멤버 초대 실패:', error.message);
        ApiErrorHandler.handle(error, '멤버 초대');
        return false;
    }
}
</script>