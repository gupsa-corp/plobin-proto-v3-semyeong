{{-- 로그아웃 유틸리티 (Alpine.js 컴포넌트에서 사용) --}}
<script>
// 로그아웃 유틸리티 함수 (Alpine.js에서 통합 관리)
window.logout = async function() {
    const token = localStorage.getItem('auth_token') || 
                 document.querySelector('meta[name="auth-token"]')?.content;

    if (token) {
        try {
            await fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });
        } catch (error) {
            console.log('로그아웃 API 오류:', error);
        }
    }

    localStorage.removeItem('auth_token');
    localStorage.removeItem('selectedOrg');
    window.location.href = '/login';
}
</script>
