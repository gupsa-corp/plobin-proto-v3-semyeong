{{-- 사용자 정보 조회 유틸리티 (Alpine.js 컴포넌트에서 사용) --}}
<script>
// 사용자 정보 조회 유틸리티 함수 (Alpine.js에서 통합 관리)
window.fetchUserInfo = async function(token) {
    const response = await fetch('/api/auth/me', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        }
    });

    if (!response.ok) {
        throw new Error(`인증 실패: ${response.status}`);
    }

    const result = await response.json();
    return result.data;
}
</script>
