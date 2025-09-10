<script>
function customScreenSettingsPage() {
    return {
        sandboxSelected: window.currentSandboxName !== '',
        selectedCustomScreen: '{{ $currentCustomScreenSettings['screen_id'] ?? '' }}',
        customScreens: window.customScreensData || [],
        loading: false,
        error: null,

        init() {
            // 샌드박스가 선택된 경우 스크린 목록을 로드
            if (this.sandboxSelected && window.currentSandboxName) {
                this.loadCustomScreens();
            }
        },

        async loadCustomScreens() {
            // 백엔드에서 이미 데이터를 전달받았으므로 API 호출 불필요
            this.customScreens = window.customScreensData || [];
            this.loading = false;
            this.error = null;

            console.log(`${this.customScreens.length}개의 화면을 로드했습니다.`);
        },

        // 스크린 미리보기 함수
        previewScreen(screenId) {
            const screen = this.customScreens.find(s => s.id == screenId);
            if (screen && window.currentSandboxName) {
                // 모든 스크린은 폴더명으로 접근
                const folderName = screen.name;
                const previewUrl = `/sandbox/${window.currentSandboxName}/${folderName}`;
                window.open(previewUrl, 'screen-preview', 'width=1200,height=800,scrollbars=yes,resizable=yes');
            }
        }
    }
}
</script>
