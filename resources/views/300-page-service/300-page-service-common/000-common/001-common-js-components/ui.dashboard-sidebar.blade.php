{{-- 대시보드 사이드바 Alpine.js 컴포넌트 --}}
<script>
/**
 * 대시보드 사이드바를 관리하는 Alpine.js 컴포넌트
 */
function dashboardSidebar() {
    return {
        // State
        currentOrg: null,
        organizations: [],
        filteredOrganizations: [],
        isDropdownOpen: false,
        isModalOpen: false,
        searchQuery: '',
        isLoading: false,
        formErrors: {},

        // Form data
        newOrgName: '',
        newOrgUrl: '',

        // Mobile
        isMobileSidebarOpen: false,

        init() {
            this.loadOrganizations();
            this.setupNavigation();
            this.restoreSavedOrganization();
        },

        // Organization Management

        async loadOrganizations() {
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
                    this.organizations = data.data.organizations || [];
                    this.filteredOrganizations = [...this.organizations];
                } else {
                    console.error('조직 목록 로드 실패:', response.status);
                    this.organizations = [];
                    this.filteredOrganizations = [];
                }
            } catch (error) {
                console.error('조직 목록 로드 중 오류:', error);
                this.organizations = [];
                this.filteredOrganizations = [];
            } finally {
                this.isLoading = false;
            }
        },

        filterOrganizations() {
            const query = this.searchQuery.toLowerCase();
            this.filteredOrganizations = this.organizations.filter(org =>
                org.name.toLowerCase().includes(query) ||
                org.url.toLowerCase().includes(query)
            );
        },

        get hasOrganizations() {
            return this.organizations.length > 0;
        },

        selectOrganization(orgId, orgUrl) {
            this.currentOrg = { id: orgId, url: orgUrl };

            // 로컬 스토리지에 저장
            localStorage.setItem('selectedOrg', JSON.stringify(this.currentOrg));

            // 드롭다운 닫기
            this.closeDropdown();

            // 조직 변경 이벤트 실행
            this.onOrganizationChange(orgId);
        },

        onOrganizationChange(orgId) {
            console.log('조직이 변경되었습니다:', orgId);

            // 대시보드 데이터 새로고침 등
            if (typeof window.refreshDashboard === 'function') {
                window.refreshDashboard(orgId);
            }
        },

        // Dropdown Management
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
            if (this.isDropdownOpen) {
                this.searchQuery = '';
                this.filteredOrganizations = [...this.organizations];
                this.$nextTick(() => {
                    const searchInput = this.$refs.orgSearch;
                    if (searchInput) searchInput.focus();
                });
            }
        },

        closeDropdown() {
            this.isDropdownOpen = false;
            this.searchQuery = '';
            this.filteredOrganizations = [...this.organizations];
        },

        get selectedOrgDisplay() {
            return this.currentOrg ? `@${this.currentOrg.url}` : '조직 선택';
        },

        // Modal Management
        showCreateOrgModal() {
            this.isModalOpen = true;
            this.newOrgName = '';
            this.newOrgUrl = '';
            this.formErrors = {};

            this.$nextTick(() => {
                const nameInput = this.$refs.orgNameInput;
                if (nameInput) nameInput.focus();
            });
        },

        closeModal() {
            this.isModalOpen = false;
            this.newOrgName = '';
            this.newOrgUrl = '';
            this.formErrors = {};
        },

        handleUrlInput() {
            this.newOrgUrl = this.newOrgUrl.replace(/[^a-zA-Z]/g, '');
        },

        async createOrganization() {
            if (!this.newOrgName.trim() || !this.newOrgUrl.trim()) {
                this.formErrors = { general: ['모든 필드를 입력해주세요.'] };
                return;
            }

            this.isLoading = true;
            this.formErrors = {};

            try {
                const response = await fetch('/api/organizations/create', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.getAuthToken()}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.newOrgName,
                        url_path: this.newOrgUrl
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.closeModal();
                    await this.loadOrganizations();
                    this.selectOrganization(data.data.id, data.data.url);
                    this.showToast('조직이 성공적으로 생성되었습니다.', 'success');
                } else {
                    this.formErrors = data.errors || { general: [data.message] };
                }
            } catch (error) {
                console.error('조직 생성 중 오류:', error);
                this.formErrors = { general: ['네트워크 오류가 발생했습니다.'] };
            } finally {
                this.isLoading = false;
            }
        },

        // Mobile & Navigation
        toggleMobileSidebar() {
            this.isMobileSidebarOpen = !this.isMobileSidebarOpen;
        },

        setupNavigation() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPath) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        },

        restoreSavedOrganization() {
            const savedOrg = localStorage.getItem('selectedOrg');
            if (savedOrg) {
                try {
                    this.currentOrg = JSON.parse(savedOrg);
                } catch (e) {
                    localStorage.removeItem('selectedOrg');
                }
            }
        },

        // Utilities
        getAuthToken() {
            const token = document.querySelector('meta[name="auth-token"]')?.content;
            if (token) return token;
            return localStorage.getItem('auth_token') || '';
        },

        showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
            } text-white`;
            toast.textContent = message;

            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }
}

// Alpine.js 컴포넌트로 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardSidebar', dashboardSidebar);
});
</script>

