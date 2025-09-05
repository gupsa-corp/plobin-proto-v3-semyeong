{{-- 인증 헤더 생성 함수 --}}
<script>
/**
 * 인증 헤더를 가져옵니다
 */
function getAuthHeaders() {
    const token = localStorage.getItem('auth_token');
    return {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
}
</script>