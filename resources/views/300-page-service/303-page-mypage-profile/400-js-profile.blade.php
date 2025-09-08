<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profilePage', () => ({
        loading: true,
        error: null,
        profile: {},
        organizations: [],
        
        init() {
            this.loadData();
        },
        
        async loadData() {
            try {
                this.loading = true;
                this.error = null;
                
                // 프로필 정보와 조직 정보를 병렬로 로드
                await Promise.all([
                    this.loadProfile(),
                    this.loadOrganizations()
                ]);
                
            } catch (error) {
                console.error('데이터 로딩 오류:', error);
                this.error = '데이터를 불러오는 중 오류가 발생했습니다.';
            } finally {
                this.loading = false;
            }
        },
        
        async loadProfile() {
            try {
                const response = await fetch('/api/user/profile', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    this.profile = data.data;
                } else {
                    throw new Error(data.message || '프로필 정보를 불러올 수 없습니다.');
                }
                
            } catch (error) {
                console.error('프로필 로딩 오류:', error);
                throw new Error('프로필 정보를 불러오는 중 오류가 발생했습니다.');
            }
        },

        async loadOrganizations() {
            try {
                const response = await fetch('/api/organizations/list', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    this.organizations = data.data || [];
                } else {
                    console.warn('조직 정보 로딩 실패:', data.message);
                    this.organizations = [];
                }
                
            } catch (error) {
                console.error('조직 정보 로딩 오류:', error);
                // 조직 정보는 필수가 아니므로 에러를 던지지 않음
                this.organizations = [];
            }
        }
    }));
});
</script>