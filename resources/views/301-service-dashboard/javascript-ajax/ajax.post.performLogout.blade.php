<script>
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
