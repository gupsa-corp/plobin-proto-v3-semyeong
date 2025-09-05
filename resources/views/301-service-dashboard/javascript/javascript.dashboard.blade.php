{{-- 대시보드 Alpine.js 컴포넌트 (경량화 버전) --}}
<script>
/**
 * 대시보드를 관리하는 Alpine.js 컴포넌트 (commons-javascript 기반)
 */
function dashboardController() {
    return {
        // State
        currentOrg: null,
        organizations: [],
        isLoading: false,
        projects: [],
        showOrgSelection: false,

        init() {
            this.checkAuth();
            this.restoreOrganization();
            this.initializeDashboard();
        },

        // Authentication
        async checkAuth() {
            const token = this.getAuthToken();
            if (!token) {
                this.redirectToLogin();
                return;
            }

            try {
                const userInfo = await this.fetchUserInfo(token);
                console.log('사용자 인증 완료:', userInfo);
            } catch (error) {
                console.error('인증 실패:', error);
                this.redirectToLogin();
            }
        },

        async fetchUserInfo(token) {
            const response = await fetch('/api/auth/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`인증 실패: ${response.status}`);
            }

            return (await response.json()).data;
        },

        // Organization Management
        async loadOrganizations() {
            if (this.isLoading) return;

            try {
                this.isLoading = true;
                const response = await fetch('/api/organizations/list', {
                    headers: {
                        'Authorization': `Bearer ${this.getAuthToken()}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.organizations = data.data?.organizations || [];
                }
            } catch (error) {
                console.error('조직 목록 로드 오류:', error);
                this.organizations = [];
            } finally {
                this.isLoading = false;
            }
        },

        selectOrganization(orgId, orgUrl) {
            const selectedOrg = this.organizations.find(org => org.id === orgId);
            if (!selectedOrg) return;

            this.currentOrg = selectedOrg;
            localStorage.setItem('selectedOrg', JSON.stringify(selectedOrg));
            
            this.showMainDashboard();
            this.loadProjects(orgId);
        },

        // Dashboard Display
        async initializeDashboard() {
            await this.loadOrganizations();
            
            if (this.currentOrg && this.organizations.length > 0) {
                this.showMainDashboard();
                this.loadProjects(this.currentOrg.id);
            } else {
                this.showOrganizationSelection();
            }
        },

        showOrganizationSelection() {
            this.showOrgSelection = true;
        },

        showMainDashboard() {
            this.showOrgSelection = false;
        },

        // Project Management  
        async loadProjects(orgId) {
            try {
                this.isLoading = true;
                // 임시 데이터 (실제 API 구현 시 수정)
                await new Promise(resolve => setTimeout(resolve, 500));
                this.projects = [];
            } catch (error) {
                console.error('프로젝트 로드 오류:', error);
                this.projects = [];
            } finally {
                this.isLoading = false;
            }
        },

        // Logout
        async logout() {
            await this.performLogout();
            this.redirectToLogin();
        },

        async performLogout() {
            const token = this.getAuthToken();
            if (token) {
                try {
                    await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        }
                    });
                } catch (error) {
                    console.log('로그아웃 API 오류:', error);
                }
            }
            localStorage.removeItem('auth_token');
            localStorage.removeItem('selectedOrg');
        },

        // Utilities
        restoreOrganization() {
            const saved = localStorage.getItem('selectedOrg');
            if (saved) {
                try {
                    this.currentOrg = JSON.parse(saved);
                } catch (e) {
                    localStorage.removeItem('selectedOrg');
                }
            }
        },

        getAuthToken() {
            const token = document.querySelector('meta[name="auth-token"]')?.content;
            return token || localStorage.getItem('auth_token') || '';
        },

        redirectToLogin() {
            window.location.href = '/login';
        },

        get selectedOrgDisplay() {
            return this.currentOrg ? `@${this.currentOrg.url || this.currentOrg.urlPath}` : '조직 선택';
        },

        get hasOrganizations() {
            return this.organizations.length > 0;
        },

        get hasProjects() {
            return this.projects.length > 0;
        }
    }
}

// Alpine.js 컴포넌트 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardController', dashboardController);
});
</script>