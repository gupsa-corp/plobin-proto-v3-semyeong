{{-- 인증 관리 클래스 --}}
<script>
/**
 * 사용자 인증을 관리하는 클래스
 */
class AuthenticationManager {
    constructor() {
        this.token = null;
        this.userData = null;
    }

    /**
     * 인증 상태를 확인합니다
     */
    async checkAuth() {
        this.token = localStorage.getItem('auth_token');

        console.log('저장된 토큰:', this.token);

        // 토큰이 없으면 즉시 로그인 페이지로 리다이렉트
        if (!this.token) {
            console.log('토큰이 없습니다. 로그인 페이지로 이동합니다.');
            window.location.href = '/login';
            return;
        }

        try {
            this.userData = await fetchUserInfo(this.token);
            this.showDashboard(this.userData);
        } catch (error) {
            // 모든 에러에 대해 ApiErrorHandler에서 처리 (401인 경우 자동 리다이렉트)
            ApiErrorHandler.handle(error, '인증 확인');
        }
    }

    /**
     * 대시보드를 표시합니다
     * @param {Object} userData - 사용자 데이터
     */
    showDashboard(userData) {
        this.updateUserInfo(userData);
        this.loadOrganizations();
    }

    /**
     * 사용자 정보를 UI에 업데이트합니다
     * @param {Object} userData - 사용자 데이터
     */
    updateUserInfo(userData) {
        if (!userData || !userData.name) return;

        // userName 요소가 존재하는지 확인 후 업데이트
        const userNameElement = document.getElementById('userName');
        if (userNameElement) {
            userNameElement.textContent = userData.name;
        }

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

        console.log('대시보드 표시됨:', userData);
    }

    /**
     * 조직 목록을 로드합니다
     */
    loadOrganizations() {
        const organizationManager = new OrganizationManager();
        organizationManager.loadOrganizations();
    }

    /**
     * 로그아웃을 수행합니다
     */
    async logout() {
        try {
            await performLogout();
        } finally {
            window.location.href = '/login';
        }
    }
}
</script>