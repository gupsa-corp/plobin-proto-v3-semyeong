{{-- 조직 대시보드 메인 컨트롤러 --}}
<script>
/**
 * 조직 대시보드의 메인 컨트롤러 클래스
 */
class OrganizationDashboardController {
    constructor() {
        this.dataManager = new OrganizationDataManager();
        this.organizationId = this.extractOrganizationId();
        this.organizationData = null;
        this.init();
    }

    /**
     * URL에서 조직 ID를 추출합니다
     */
    extractOrganizationId() {
        const path = window.location.pathname;
        const matches = path.match(/\/organizations\/(\d+)\/dashboard/);
        if (matches && matches[1]) {
            return parseInt(matches[1]);
        }
        
        // URL에서 추출 실패시 로컬 스토리지에서 시도
        const storedOrgId = localStorage.getItem('selected_organization_id');
        if (storedOrgId) {
            return parseInt(storedOrgId);
        }
        
        console.error('조직 ID를 찾을 수 없습니다.');
        return null;
    }

    /**
     * 컨트롤러를 초기화합니다
     */
    async init() {
        if (!this.organizationId) {
            this.showError('조직 정보를 찾을 수 없습니다.');
            return;
        }

        this.setupEventListeners();
        await this.loadOrganizationData();
        this.checkAuthentication();
    }

    /**
     * 이벤트 리스너를 설정합니다
     */
    setupEventListeners() {
        // 멤버 초대 버튼
        const inviteMemberBtn = document.getElementById('inviteMemberBtn');
        if (inviteMemberBtn) {
            inviteMemberBtn.addEventListener('click', () => this.showInviteMemberModal());
        }

        // 조직 설정 버튼
        const orgSettingsBtn = document.getElementById('orgSettingsBtn');
        if (orgSettingsBtn) {
            orgSettingsBtn.addEventListener('click', () => this.showOrganizationSettings());
        }

        // 로그아웃 버튼 이벤트
        document.addEventListener('click', (e) => {
            if (e.target.getAttribute('href') === '/logout') {
                e.preventDefault();
                this.logout();
            }
        });
    }

    /**
     * 조직 데이터를 로드합니다
     */
    async loadOrganizationData() {
        try {
            // 로컬 스토리지에서 기본 조직 정보 먼저 로드
            this.loadStoredOrganizationInfo();
            
            // 서버에서 상세 데이터 로드
            const orgData = await this.dataManager.loadOrganizationDetail(this.organizationId);
            if (orgData) {
                this.organizationData = orgData;
                this.updateOrganizationInfo(orgData);
            }

            // 통계 데이터 로드
            await this.loadDashboardStats();
            
            // 최근 활동 로드
            await this.loadRecentActivities();
            
            // 최근 프로젝트 로드
            await this.loadRecentProjects();
            
        } catch (error) {
            console.error('조직 데이터 로드 실패:', error);
            this.showError('조직 정보를 불러오는데 실패했습니다.');
        }
    }

    /**
     * 로컬 스토리지에서 조직 정보를 로드합니다
     */
    loadStoredOrganizationInfo() {
        const storedOrg = localStorage.getItem('selected_organization');
        if (storedOrg) {
            try {
                const orgData = JSON.parse(storedOrg);
                this.updateOrganizationInfo(orgData);
            } catch (error) {
                console.error('로컬 스토리지 조직 정보 파싱 실패:', error);
            }
        }
    }

    /**
     * 조직 정보를 UI에 업데이트합니다
     */
    updateOrganizationInfo(orgData) {
        // 조직 이름 업데이트
        const orgNameElement = document.getElementById('orgName');
        if (orgNameElement) {
            orgNameElement.textContent = orgData.name || '조직명 없음';
        }

        // 조직 코드 업데이트
        const orgCodeElement = document.getElementById('orgCode');
        if (orgCodeElement) {
            const orgCode = orgData.code || orgData.urlPath || orgData.slug || `ID: ${orgData.id}`;
            orgCodeElement.textContent = orgCode;
        }

        // 조직 아바타 업데이트
        const orgAvatarElement = document.getElementById('orgAvatar');
        if (orgAvatarElement) {
            const avatarColor = orgData.avatar_color || orgData.color || '#0DC8AF';
            const avatarText = orgData.avatar || orgData.name?.charAt(0)?.toUpperCase() || '?';
            
            orgAvatarElement.style.backgroundColor = avatarColor;
            orgAvatarElement.textContent = avatarText;
        }
    }

    /**
     * 대시보드 통계를 로드합니다
     */
    async loadDashboardStats() {
        try {
            const stats = await this.dataManager.loadDashboardStats(this.organizationId);
            
            if (stats) {
                // 멤버 수
                const memberCountElement = document.getElementById('memberCount');
                if (memberCountElement) {
                    memberCountElement.textContent = stats.memberCount || '0';
                }

                // 프로젝트 수
                const projectCountElement = document.getElementById('projectCount');
                if (projectCountElement) {
                    projectCountElement.textContent = stats.projectCount || '0';
                }

                // 완료된 작업 수
                const completedTasksElement = document.getElementById('completedTasks');
                if (completedTasksElement) {
                    completedTasksElement.textContent = stats.completedTasks || '0';
                }

                // 활성 사용자 수
                const activeUsersElement = document.getElementById('activeUsers');
                if (activeUsersElement) {
                    activeUsersElement.textContent = stats.activeUsers || '0';
                }
            }
        } catch (error) {
            console.error('대시보드 통계 로드 실패:', error);
        }
    }

    /**
     * 최근 활동을 로드합니다
     */
    async loadRecentActivities() {
        try {
            const activities = await this.dataManager.loadRecentActivities(this.organizationId);
            const container = document.getElementById('recentActivities');
            
            if (!container) return;

            if (activities && activities.length > 0) {
                container.innerHTML = activities.map(activity => this.createActivityElement(activity)).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <p>최근 활동이 없습니다.</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('최근 활동 로드 실패:', error);
        }
    }

    /**
     * 최근 프로젝트를 로드합니다
     */
    async loadRecentProjects() {
        try {
            const projects = await this.dataManager.loadRecentProjects(this.organizationId);
            const container = document.getElementById('recentProjects');
            
            if (!container) return;

            if (projects && projects.length > 0) {
                container.innerHTML = projects.map(project => this.createProjectElement(project)).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <p>프로젝트가 없습니다.</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('최근 프로젝트 로드 실패:', error);
        }
    }

    /**
     * 활동 요소를 생성합니다
     */
    createActivityElement(activity) {
        return `
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-blue-600">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${activity.title || '활동 제목'}</p>
                    <p class="text-xs text-gray-500">${activity.time || '시간 정보 없음'}</p>
                </div>
            </div>
        `;
    }

    /**
     * 프로젝트 요소를 생성합니다
     */
    createProjectElement(project) {
        const statusColor = this.getProjectStatusColor(project.status);
        return `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-600">
                            <path d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${project.name || '프로젝트 이름'}</p>
                        <p class="text-xs text-gray-500">${project.members || 0}명 참여</p>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full ${statusColor}">
                    ${project.status || '상태 없음'}
                </span>
            </div>
        `;
    }

    /**
     * 프로젝트 상태에 따른 색상을 반환합니다
     */
    getProjectStatusColor(status) {
        switch (status) {
            case 'active': case '진행중':
                return 'bg-green-100 text-green-800';
            case 'paused': case '일시정지':
                return 'bg-yellow-100 text-yellow-800';
            case 'completed': case '완료':
                return 'bg-blue-100 text-blue-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * 멤버 초대 모달을 표시합니다
     */
    showInviteMemberModal() {
        console.log('멤버 초대 모달 표시');
        // TODO: 멤버 초대 모달 구현
        alert('멤버 초대 기능은 준비 중입니다.');
    }

    /**
     * 조직 설정을 표시합니다
     */
    showOrganizationSettings() {
        console.log('조직 설정 표시');
        // TODO: 조직 설정 페이지로 이동
        alert('조직 설정 기능은 준비 중입니다.');
    }

    /**
     * 인증 상태를 확인합니다
     */
    checkAuthentication() {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            console.warn('인증 토큰이 없습니다.');
            window.location.href = '/login';
        }
    }

    /**
     * 로그아웃을 처리합니다
     */
    logout() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_info');
        localStorage.removeItem('selected_organization');
        localStorage.removeItem('selected_organization_id');
        window.location.href = '/login';
    }

    /**
     * 에러 메시지를 표시합니다
     */
    showError(message) {
        console.error('에러:', message);
        // TODO: 에러 모달이나 토스트 메시지 구현
        alert(`에러: ${message}`);
    }
}

// 페이지 로드 시 OrganizationDashboardController 인스턴스 생성
document.addEventListener('DOMContentLoaded', () => {
    new OrganizationDashboardController();
});
</script>