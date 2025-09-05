{{-- 모든 데이터 로딩 함수 --}}
<script>
/**
 * 모든 데이터를 로드합니다
 */
async function loadAllData() {
    try {
        this.isLoading = true;
        this.hasError = false;
        this.errorMessage = '';

        // 동시에 모든 데이터 로드
        await Promise.all([
            this.loadOrganizationData(),
            this.loadDashboardStats(),
            this.loadRecentActivities(),
            this.loadRecentProjects()
        ]);

    } catch (error) {
        console.error('데이터 로드 실패:', error);
        this.hasError = true;
        this.errorMessage = '데이터를 불러오는데 실패했습니다.';
    } finally {
        this.isLoading = false;
    }
}
</script>