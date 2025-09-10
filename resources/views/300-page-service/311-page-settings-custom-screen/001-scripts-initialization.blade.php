<script>
// Initialize custom screens data safely before Alpine.js loads
// 백엔드에서 전달받은 실제 커스텀 화면 데이터 사용
window.customScreensData = @json($customScreens ?? []);
window.currentSandboxName = @json($currentSandboxName ?? '');
</script>
