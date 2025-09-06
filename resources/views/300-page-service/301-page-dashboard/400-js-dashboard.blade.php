{{-- 대시보드 메인 컴포넌트 --}}
<script>
/**
 * 대시보드 메인 컴포넌트 - 순수 대시보드 기능만 포함
 * 기능: 대시보드 데이터 로드 및 표시
 */
function dashboardMain() {
    return {
        // 상태 변수들
        isLoading: false,
        dashboardData: {},

        // 초기화
        init() {
            this.loadDashboardData();
        },

        // 대시보드 데이터 로드
        async loadDashboardData() {
            try {
                this.isLoading = true;
                // TODO: 실제 API 구현 시 대시보드 데이터 로드
                await new Promise(resolve => setTimeout(resolve, 500));
                
                console.log('대시보드 데이터 로드 준비 완료');
                // 실제 구현 시에는 다음과 같은 데이터를 로드:
                // - 프로젝트 요약 통계
                // - 최근 활동 내역
                // - 팀 현황
                // - 중요 알림
                
            } catch (error) {
                console.error('대시보드 데이터 로드 실패:', error);
            } finally {
                this.isLoading = false;
            }
        }
    }
}

// Alpine.js 컴포넌트 등록 - 즉시 실행
(function() {
    function registerComponent() {
        if (window.Alpine) {
            Alpine.data('dashboardMain', dashboardMain);
        } else {
            setTimeout(registerComponent, 10);
        }
    }
    registerComponent();
})();

// 또한 alpine:init 이벤트에도 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardMain', dashboardMain);
});
</script>