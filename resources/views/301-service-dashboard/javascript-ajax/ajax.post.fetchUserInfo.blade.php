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
        throw new Error(`API 오류: ${response.status}`);
    }

    const result = await response.json();
    return result.data;
}
</script>
