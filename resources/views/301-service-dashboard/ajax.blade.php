{{-- 대시보드 인증 AJAX --}}
<script>
// 사용자 정보 조회 AJAX
async function fetchUserInfo(token) {
    const response = await fetch('/api/auth/me', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    if (!response.ok) {
        throw new Error('인증 실패');
    }

    const result = await response.json();
    return result.data;
}

// 로그아웃 AJAX
async function performLogout() {
    const token = localStorage.getItem('auth_token');
    
    if (token) {
        try {
            await fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        } catch (error) {
            console.log('로그아웃 API 오류:', error);
        }
    }
    
    // 로컬 토큰 제거
    localStorage.removeItem('auth_token');
}
</script>