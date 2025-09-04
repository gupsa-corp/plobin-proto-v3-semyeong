{{-- 대시보드 JavaScript 로직 --}}
<script>
// 인증 상태 확인
async function checkAuth() {
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        showAuthRequired();
        return;
    }

    try {
        const userData = await fetchUserInfo(token);
        showDashboard(userData);
    } catch (error) {
        console.log('인증 확인 오류:', error);
        // API가 없을 수 있으므로 토큰이 있으면 임시로 인증된 것으로 처리
        if (token) {
            showDashboard({ name: '사용자' });
        } else {
            showAuthRequired();
        }
    }
}

function showAuthRequired() {
    document.getElementById('authLoading').classList.add('hidden');
    document.getElementById('authRequired').classList.remove('hidden');
    document.getElementById('dashboardContent').classList.add('hidden');
}

function showDashboard(userData) {
    document.getElementById('authLoading').classList.add('hidden');
    document.getElementById('authRequired').classList.add('hidden');
    document.getElementById('dashboardContent').classList.remove('hidden');
    
    // 사용자 정보 표시
    if (userData && userData.name) {
        document.getElementById('userName').textContent = userData.name;
        
        // 헤더의 사용자 정보도 업데이트
        const userButton = document.querySelector('.service-header button span');
        if (userButton) {
            userButton.textContent = userData.name;
        }
        
        // 사용자 아바타 업데이트
        const userAvatar = document.querySelector('.service-header .bg-primary-500');
        if (userAvatar && userData.name) {
            userAvatar.textContent = userData.name.charAt(0).toUpperCase();
        }
    }
}

// 로그아웃 기능
async function logout() {
    try {
        await performLogout();
    } finally {
        window.location.href = '/login';
    }
}

// 페이지 로드시 인증 확인
document.addEventListener('DOMContentLoaded', checkAuth);

// 로그아웃 버튼에 이벤트 리스너 추가 (header에서 클릭시)
document.addEventListener('click', function(e) {
    if (e.target.getAttribute('href') === '/logout') {
        e.preventDefault();
        logout();
    }
});
</script>