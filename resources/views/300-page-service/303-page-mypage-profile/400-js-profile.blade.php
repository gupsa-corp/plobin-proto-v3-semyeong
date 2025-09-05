<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profilePage', () => ({
        init() {
            this.loadProfile();
        },
        
        async loadProfile() {
            // 프로필 정보 로드 로직
        }
    }));
});
</script>