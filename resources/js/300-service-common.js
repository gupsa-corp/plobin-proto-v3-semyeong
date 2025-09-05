// Dashboard Sidebar Alpine.js Component
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

// 모달 스타일 추가
const modalStyles = `
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 24px 16px;
        border-bottom: 1px solid var(--sidebar-border);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-body {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .required {
        color: #e53e3e;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--sidebar-border);
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
    }

    .form-group input:focus {
        border-color: var(--primary-color);
    }

    .input-prefix {
        position: relative;
    }

    .prefix {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 14px;
    }

    .input-prefix input {
        padding-left: 28px;
    }

    .form-help {
        margin-top: 4px;
        font-size: 12px;
        color: var(--text-secondary);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn-cancel, .btn-create {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--sidebar-border);
        color: var(--text-primary);
    }

    .btn-cancel:hover {
        background: var(--hover-bg);
    }

    .btn-create {
        background: var(--primary-color);
        border: 1px solid var(--primary-color);
        color: white;
    }

    .btn-create:hover {
        background: #0bb39a;
    }

    .btn-create:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
`;

// 스타일 추가
if (!document.getElementById('modal-styles')) {
    const style = document.createElement('style');
    style.id = 'modal-styles';
    style.textContent = modalStyles;
    document.head.appendChild(style);
}

// Alpine.js global function - use with x-data="dashboardSidebar()"
// No need for DOMContentLoaded - Alpine.js handles initialization
