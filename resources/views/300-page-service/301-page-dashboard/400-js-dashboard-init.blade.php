{{-- 대시보드 초기화 관리 --}}
<script>
/**
 * 대시보드 초기화를 담당하는 Alpine.js 컴포넌트
 * 기능: 페이지 로드 시 대시보드 초기화
 */
function dashboardInit() {
    return {
        init() {
            // 대시보드 초기화
            this.initializeDashboard();
        },

        initializeDashboard() {
            console.log('대시보드 초기화 완료');
            // TODO: 실제 구현 시 대시보드 관련 초기화 로직 추가
        }
    };
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardInit', dashboardInit);
});
</script>