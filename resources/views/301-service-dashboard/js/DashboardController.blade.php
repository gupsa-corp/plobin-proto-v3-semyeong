{{-- 대시보드 메인 컨트롤러 --}}
<script>
/**
 * 대시보드의 메인 컨트롤러 클래스
 */
class DashboardController {
    constructor() {
        this.authManager = new AuthenticationManager();
        this.modalManager = new OrganizationModalManager();
        // 전역 변수로 설정하여 HTML에서 접근 가능하도록 함
        window.organizationManager = new OrganizationManager();
        this.init();
    }

    /**
     * 컨트롤러를 초기화합니다
     */
    init() {
        this.setupEventListeners();
        this.checkAuthentication();
        this.initializeDashboard();
    }

    /**
     * 이벤트 리스너를 설정합니다
     */
    setupEventListeners() {
        // 모달 이벤트 리스너 설정
        this.modalManager.setupEventListeners();

        // 로그아웃 버튼 이벤트
        document.addEventListener('click', (e) => {
            if (e.target.getAttribute('href') === '/logout') {
                e.preventDefault();
                this.authManager.logout();
            }
        });
    }

    /**
     * 인증 상태를 확인합니다
     */
    checkAuthentication() {
        this.authManager.checkAuth();
    }

    /**
     * 대시보드를 초기화합니다 (조직 선택 여부에 따라 적절한 화면 표시)
     */
    initializeDashboard() {
        const selectedOrg = localStorage.getItem('selectedOrganization');
        
        if (selectedOrg) {
            try {
                const orgData = JSON.parse(selectedOrg);
                this.showMainDashboard(orgData);
            } catch (error) {
                console.error('선택된 조직 정보 파싱 오류:', error);
                localStorage.removeItem('selectedOrganization');
                this.showOrganizationSelection();
            }
        } else {
            this.showOrganizationSelection();
        }
    }

    /**
     * 조직 선택 화면을 표시합니다
     */
    showOrganizationSelection() {
        document.getElementById('organizationSelectionScreen').style.display = 'block';
        document.getElementById('mainDashboardScreen').style.display = 'none';
        
        // 조직 목록 로드
        window.organizationManager.loadOrganizations();
    }

    /**
     * 메인 대시보드를 표시합니다
     * @param {Object} orgData - 선택된 조직 데이터
     */
    showMainDashboard(orgData) {
        document.getElementById('organizationSelectionScreen').style.display = 'none';
        document.getElementById('mainDashboardScreen').style.display = 'block';
        
        // 사이드바 조직 선택기 업데이트
        const orgText = document.querySelector('.org-text');
        if (orgText && orgData.url) {
            orgText.textContent = orgData.url;
        }
        
        // 대시보드 조직명 표시 업데이트
        const orgNameDisplay = document.getElementById('orgNameDisplay');
        if (orgNameDisplay && orgData.name) {
            orgNameDisplay.textContent = `${orgData.name} 조직의 대시보드입니다`;
        }
        
        // 프로젝트 로드
        this.loadProjects(orgData.id);
        
        console.log('메인 대시보드 표시됨:', orgData);
    }

    /**
     * 프로젝트 목록을 로드합니다
     * @param {number} orgId - 조직 ID
     */
    async loadProjects(orgId) {
        console.log('프로젝트 로드 시작:', orgId);
        
        const projectsList = document.getElementById('projectsList');
        const emptyState = document.getElementById('emptyProjectsState');
        
        if (!projectsList) {
            console.error('projectsList 요소를 찾을 수 없습니다');
            return;
        }

        // 프로젝트 API가 아직 없으므로 임시 데이터 표시
        // TODO: 실제 프로젝트 API 구현 후 수정
        try {
            // 로딩 상태 표시
            projectsList.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-500 mx-auto"></div>
                    <p class="mt-2 text-gray-500">프로젝트를 불러오는 중...</p>
                </div>
            `;

            // 잠시 대기 (실제 API 호출 시뮬레이션)
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // 임시 프로젝트 데이터 (나중에 실제 API로 교체)
            const mockProjects = [];
            
            if (mockProjects.length === 0) {
                // 빈 상태 표시
                projectsList.innerHTML = `
                    <div class="text-center py-8" id="emptyProjectsState">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">프로젝트가 없습니다</h3>
                        <p class="mt-2 text-gray-500">새 프로젝트를 생성하여 시작하세요</p>
                        <button class="mt-4 bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200" onclick="alert('프로젝트 생성 기능은 개발 중입니다.')">
                            첫 번째 프로젝트 만들기
                        </button>
                    </div>
                `;
                console.log('빈 프로젝트 상태 표시');
            } else {
                // 프로젝트 목록 표시 (향후 구현)
                this.renderProjectsList(mockProjects);
            }
        } catch (error) {
            console.error('프로젝트 로드 중 오류:', error);
            projectsList.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">프로젝트를 불러오는데 실패했습니다.</p>
                </div>
            `;
        }
    }

    /**
     * 프로젝트 목록을 렌더링합니다
     * @param {Array} projects - 프로젝트 배열
     */
    renderProjectsList(projects) {
        const projectsList = document.getElementById('projectsList');
        if (!projectsList || projects.length === 0) return;

        projectsList.innerHTML = `
            <div class="space-y-4">
                ${projects.map(project => `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">${project.name}</h4>
                                <p class="text-gray-600 mt-1">${project.description}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <span>마지막 업데이트: ${project.updated_at}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    활성
                                </span>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

// 페이지 로드 시 DashboardController 인스턴스 생성
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardController = new DashboardController();
});
</script>