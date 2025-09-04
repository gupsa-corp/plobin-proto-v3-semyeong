{{-- 대시보드 메인 컨트롤러 --}}
<script>
/**
 * 대시보드의 메인 컨트롤러 클래스
 */
class DashboardController {
    constructor() {
        this.authManager = new AuthenticationManager();
        this.modalManager = new OrganizationModalManager();
        this.init();
    }

    /**
     * 컨트롤러를 초기화합니다
     */
    init() {
        this.setupEventListeners();
        this.checkAuthentication();
    }

    /**
     * 이벤트 리스너를 설정합니다
     */
    setupEventListeners() {
        // 모달 이벤트 리스너 설정
        this.modalManager.setupEventListeners();

        // 로그아웃 버튼 이벤트
        document.addEventListener('click', (e) => {
            if (e.target.getAttribute('href') === '/logout') {
                e.preventDefault();
                this.authManager.logout();
            }
        });
    }

    /**
     * 인증 상태를 확인합니다
     */
    checkAuthentication() {
        this.authManager.checkAuth();
    }
}

// 페이지 로드 시 DashboardController 인스턴스 생성
document.addEventListener('DOMContentLoaded', () => {
    new DashboardController();
});
</script>