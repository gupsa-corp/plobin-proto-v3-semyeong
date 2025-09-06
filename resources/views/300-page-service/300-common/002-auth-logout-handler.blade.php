{{-- 로그아웃 처리 공통 함수 --}}
<script>
/**
 * 로그아웃 처리를 담당하는 공통 함수
 * @param {string} redirectUrl - 로그아웃 후 리다이렉트할 URL (기본값: /login)
 * @returns {Promise<void>}
 */
async function handleLogout(redirectUrl = '/login') {
    try {
        // 공통 AJAX POST 함수 사용
        if (typeof ajaxPost === 'function') {
            await ajaxPost('/api/auth/logout', {}, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include'
            });
        } else {
            // fallback: 직접 fetch 사용
            await fetch('/api/auth/logout', {
                method: 'POST',
                headers: getApiHeaders({ 'X-Requested-With': 'XMLHttpRequest' }),
                credentials: 'include'
            });
        }

        // 응답과 상관없이 클라이언트 토큰 제거
        if (typeof removeAuthToken === 'function') {
            removeAuthToken();
        }

        // AuthHelper 토큰도 제거
        if (window.AuthHelper && typeof window.AuthHelper.removeToken === 'function') {
            window.AuthHelper.removeToken();
        }

        // 리다이렉트
        window.location.href = redirectUrl;

    } catch (error) {
        console.error('로그아웃 요청 중 오류:', error);
        
        // 에러 발생 시에도 토큰 제거하고 리다이렉트
        if (typeof removeAuthToken === 'function') {
            removeAuthToken();
        }
        
        if (window.AuthHelper && typeof window.AuthHelper.removeToken === 'function') {
            window.AuthHelper.removeToken();
        }
        
        window.location.href = redirectUrl;
    }
}

/**
 * 세션 만료 시 자동 로그아웃 처리
 * @param {string} message - 사용자에게 보여줄 메시지
 */
function handleSessionExpired(message = '세션이 만료되었습니다. 다시 로그인해주세요.') {
    // 사용자에게 알림
    if (confirm(message)) {
        handleLogout('/login');
    } else {
        // 사용자가 취소를 눌러도 강제 로그아웃
        handleLogout('/login');
    }
}

/**
 * 401 에러 시 자동 로그아웃 처리
 */
function handleUnauthorized() {
    console.warn('인증되지 않은 접근으로 인한 자동 로그아웃');
    handleSessionExpired('인증이 필요합니다. 로그인 페이지로 이동합니다.');
}
</script>