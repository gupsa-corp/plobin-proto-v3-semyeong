{{-- 조직 선택 Alpine.js 컴포넌트 (경량화 버전) --}}
<script>
/**
 * 조직 선택을 관리하는 Alpine.js 컴포넌트 (commons-javascript 기반)
 */
function organizationSelection() {
    return {
        // State
        organizations: [],
        isLoading: false,
        error: null,

        init() {
            this.loadOrganizations();
        },

        // Organization Loading
        async loadOrganizations() {
            try {
                this.isLoading = true;
                this.error = null;
                
                const token = this.getAuthToken();
                if (!token) {
                    this.error = '인증 토큰이 없습니다.';
                    return;
                }

                const response = await fetch('/api/organizations/list', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`API 오류: ${response.status}`);
                }

                const data = await response.json();
                this.organizations = this.extractOrganizations(data);
                
            } catch (error) {
                console.error('조직 목록 로드 실패:', error);
                this.error = '조직 목록을 불러오는데 실패했습니다.';
                this.organizations = [];
            } finally {
                this.isLoading = false;
            }
        },

        extractOrganizations(data) {
            if (data.success && data.data?.organizations) return data.data.organizations;
            if (data.data && Array.isArray(data.data)) return data.data;
            if (data.organizations && Array.isArray(data.organizations)) return data.organizations;
            if (Array.isArray(data)) return data;
            return [];
        },

        // Organization Selection
        selectOrganization(org) {
            // 선택된 조직 저장
            localStorage.setItem('selectedOrg', JSON.stringify(org));
            
            // 조직 대시보드로 이동
            window.location.href = `/organizations/${org.id}/dashboard`;
        },

        // Organization Display
        getOrgAvatar(org) {
            return org.avatar || org.name?.charAt(0)?.toUpperCase() || '?';
        },

        getOrgColor(org) {
            return org.avatar_color || org.color || '#0DC8AF';
        },

        getOrgCode(org) {
            return org.code || org.urlPath || org.slug || 'no-code';
        },

        // Utilities
        getAuthToken() {
            const token = document.querySelector('meta[name="auth-token"]')?.content;
            return token || localStorage.getItem('auth_token') || '';
        },

        get hasOrganizations() {
            return this.organizations.length > 0;
        },

        get isEmpty() {
            return !this.isLoading && !this.hasOrganizations && !this.error;
        }
    }
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('organizationSelection', organizationSelection);
});
</script>