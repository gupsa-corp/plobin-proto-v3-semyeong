{{-- 조직 대시보드 Alpine.js 컴포넌트 --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 필요한 클래스들이 로드될 때까지 기다린 후 Alpine 컴포넌트 등록
    const checkDependencies = () => {
        if (typeof OrganizationDataManager !== 'undefined' && typeof ApiErrorHandler !== 'undefined') {
            // 의존성이 로드되면 Alpine 컴포넌트 등록
            Alpine.data('organizationDashboard', () => ({
        // 상태 데이터
        organizationId: null,
        organizationData: null,
        dataManager: new OrganizationDataManager(),
        
        // 통계 데이터
        stats: {
            memberCount: '0',
            projectCount: '0',
            completedTasks: '0',
            activeUsers: '0'
        },
        
        // 최근 데이터
        recentActivities: [],
        recentProjects: [],
        
        // 로딩 상태
        isLoading: true,
        hasError: false,
        errorMessage: '',

        // 초기화
        init() {
            this.organizationId = this.extractOrganizationId();
            
            if (!this.organizationId) {
                this.showError('조직 정보를 찾을 수 없습니다.');
                return;
            }

            this.checkAuthentication();
            this.setupLogoutListener();
            this.loadAllData();
        },

        // URL에서 조직 ID 추출
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
        },

        // 로그아웃 링크 이벤트 설정
        setupLogoutListener() {
            document.addEventListener('click', (e) => {
                if (e.target.getAttribute('href') === '/logout') {
                    e.preventDefault();
                    this.logout();
                }
            });
        },

        // 모든 데이터 로드
        async loadAllData() {
            try {
                this.isLoading = true;
                this.hasError = false;
                
                // 로컬 스토리지에서 기본 조직 정보 먼저 로드
                this.loadStoredOrganizationInfo();
                
                // 서버에서 상세 데이터 로드
                await Promise.all([
                    this.loadOrganizationData(),
                    this.loadDashboardStats(),
                    this.loadRecentActivities(),
                    this.loadRecentProjects()
                ]);
                
            } catch (error) {
                console.error('데이터 로드 실패:', error);
                this.showError('데이터를 불러오는데 실패했습니다.');
            } finally {
                this.isLoading = false;
            }
        },

        // 로컬 스토리지에서 조직 정보 로드
        loadStoredOrganizationInfo() {
            const storedOrg = localStorage.getItem('selected_organization');
            if (storedOrg) {
                try {
                    this.organizationData = JSON.parse(storedOrg);
                } catch (error) {
                    console.error('로컬 스토리지 조직 정보 파싱 실패:', error);
                }
            }
        },

        // 조직 데이터 로드
        async loadOrganizationData() {
            try {
                const orgData = await this.dataManager.loadOrganizationDetail(this.organizationId);
                if (orgData) {
                    this.organizationData = orgData;
                }
            } catch (error) {
                console.error('조직 데이터 로드 실패:', error);
            }
        },

        // 대시보드 통계 로드
        async loadDashboardStats() {
            try {
                const stats = await this.dataManager.loadDashboardStats(this.organizationId);
                if (stats) {
                    this.stats = {
                        memberCount: stats.memberCount?.toString() || '0',
                        projectCount: stats.projectCount?.toString() || '0',
                        completedTasks: stats.completedTasks?.toString() || '0',
                        activeUsers: stats.activeUsers?.toString() || '0'
                    };
                }
            } catch (error) {
                console.error('대시보드 통계 로드 실패:', error);
            }
        },

        // 최근 활동 로드
        async loadRecentActivities() {
            try {
                const activities = await this.dataManager.loadRecentActivities(this.organizationId);
                this.recentActivities = activities || [];
            } catch (error) {
                console.error('최근 활동 로드 실패:', error);
            }
        },

        // 최근 프로젝트 로드
        async loadRecentProjects() {
            try {
                const projects = await this.dataManager.loadRecentProjects(this.organizationId);
                this.recentProjects = projects || [];
            } catch (error) {
                console.error('최근 프로젝트 로드 실패:', error);
            }
        },

        // 계산된 속성들
        get orgName() {
            return this.organizationData?.name || '조직명 없음';
        },

        get orgCode() {
            if (!this.organizationData) return '';
            return this.organizationData.code || 
                   this.organizationData.urlPath || 
                   this.organizationData.slug || 
                   `ID: ${this.organizationData.id}`;
        },

        get orgAvatarStyle() {
            if (!this.organizationData) return 'background-color: #0DC8AF';
            const color = this.organizationData.avatar_color || 
                         this.organizationData.color || '#0DC8AF';
            return `background-color: ${color}`;
        },

        get orgAvatarText() {
            if (!this.organizationData) return '?';
            return this.organizationData.avatar || 
                   this.organizationData.name?.charAt(0)?.toUpperCase() || '?';
        },

        // 프로젝트 상태 색상
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
        },

        // 액션 메서드들
        showInviteMemberModal() {
            console.log('멤버 초대 모달 표시');
            alert('멤버 초대 기능은 준비 중입니다.');
        },

        showOrganizationSettings() {
            console.log('조직 설정 표시');
            alert('조직 설정 기능은 준비 중입니다.');
        },

        logout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_info');
            localStorage.removeItem('selected_organization');
            localStorage.removeItem('selected_organization_id');
            window.location.href = '/login';
        },

        // 인증 확인
        checkAuthentication() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                console.warn('인증 토큰이 없습니다.');
                window.location.href = '/login';
            }
        },

        // 에러 처리
        showError(message) {
            console.error('에러:', message);
            this.hasError = true;
            this.errorMessage = message;
            this.isLoading = false;
        }));
            
            // Alpine 컴포넌트를 DOM 요소에 수동으로 연결
            const mainElement = document.getElementById('organizationDashboardMain');
            if (mainElement) {
                mainElement.setAttribute('x-data', 'organizationDashboard');
                Alpine.initTree(mainElement);
            }
        } else {
            // 의존성이 아직 로드되지 않았으면 100ms 후 다시 체크
            setTimeout(checkDependencies, 100);
        }
    };
    
    // 의존성 체크 시작
    checkDependencies();
});
</script>