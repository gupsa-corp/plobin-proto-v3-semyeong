{{-- 대시보드 메인 컴포넌트 (모든 개별 모듈 통합) --}}
<script>
/**
 * 대시보드 메인 컴포넌트 - 모든 개별 모듈의 기능을 통합
 * 기능: 전체 대시보드 상태 관리 및 이벤트 조정
 */
function dashboardMain() {
    return {
        // 상태 변수들
        projects: [],
        isLoading: false,
        organizations: [],

        // 초기화
        init() {
            this.checkOrganizationStatus();
        },

        // 조직 상태 확인 및 화면 표시 (from dashboardInit)
        checkOrganizationStatus() {
            const selectedOrg = localStorage.getItem('selectedOrg');
            
            if (!selectedOrg) {
                this.showOrganizationSelection();
            } else {
                const orgData = JSON.parse(selectedOrg);
                this.showDashboard(orgData);
            }
        },

        // 조직 선택 화면 표시 (from organizationStatus)
        showOrganizationSelection() {
            const selectionScreen = document.getElementById('organizationSelectionScreen');
            if (selectionScreen) {
                selectionScreen.style.display = 'block';
            }
            this.loadOrganizations();
        },

        // 대시보드 표시 (from organizationStatus)  
        showDashboard(orgData) {
            const selectionScreen = document.getElementById('organizationSelectionScreen');
            if (selectionScreen) {
                selectionScreen.style.display = 'none';
            }
            this.loadProjects(orgData.id);
        },

        // 조직 목록 로드 (from organizationList)
        async loadOrganizations() {
            try {
                this.isLoading = true;
                
                // 실제 API 호출로 조직 목록 가져오기
                const token = localStorage.getItem('auth_token');
                const response = await fetch('/api/organizations/list', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const organizations = data.data?.organizations || [];
                
                console.log('조직 목록 로드됨:', organizations);
                this.organizations = organizations;
                this.renderOrganizations(organizations);
            } catch (error) {
                console.error('조직 목록 로드 실패:', error);
                this.renderOrganizations([]); // 실패 시 빈 목록으로 렌더링
            } finally {
                this.isLoading = false;
            }
        },

        // 조직 목록 렌더링 (from organizationList)
        renderOrganizations(organizations) {
            const orgList = document.getElementById('organizationList');
            if (!orgList) return;

            if (organizations.length === 0) {
                orgList.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 text-lg mb-4">생성된 조직이 없습니다</div>
                        <div class="text-gray-500 text-sm mb-6">새로운 조직을 생성해서 시작해보세요</div>
                        <button id="createOrganizationBtnInList" class="px-6 py-3 bg-teal-500 hover:bg-teal-600 text-white font-bold rounded-lg">
                            새조직 생성하기
                        </button>
                    </div>
                `;
                
                // 버튼 이벤트 추가 - 모달 매니저와 연동
                const createBtn = document.getElementById('createOrganizationBtnInList');
                if (createBtn) {
                    createBtn.addEventListener('click', () => this.showCreateOrganizationModal());
                }
            } else {
                orgList.innerHTML = organizations.map(org => `
                    <div class="organization-card p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer"
                         data-org-id="${org.id}">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-lg">${org.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">${org.name}</h3>
                                <p class="text-sm text-gray-500">${org.description || ''}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400">
                            멤버 ${org.members_count || 0}명
                        </div>
                    </div>
                `).join('');
                
                // 클릭 이벤트 리스너 추가
                const orgCards = orgList.querySelectorAll('.organization-card');
                orgCards.forEach(card => {
                    card.addEventListener('click', () => {
                        const orgId = card.dataset.orgId;
                        this.selectOrganization(orgId);
                    });
                });
            }
        },

        // 조직 선택 (from organizationList)
        selectOrganization(orgId) {
            const orgData = { id: orgId };
            localStorage.setItem('selectedOrg', JSON.stringify(orgData));
            window.location.href = `/organizations/${orgId}/dashboard`;
        },

        // 모달 표시 - 새로운 모달 시스템과 연동
        showCreateOrganizationModal() {
            if (window.modalUI) {
                // 새로운 시스템 사용
                window.modalUI.showCreateOrganizationModal();
            } else {
                // 기존 방식으로 폴백
                const modal = document.getElementById('createOrganizationModal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            }
        },

        // 모달 숨기기 - 새로운 모달 시스템과 연동
        hideCreateOrganizationModal() {
            if (window.modalUI) {
                // 새로운 시스템 사용
                window.modalUI.hideCreateOrganizationModal();
            } else {
                // 기존 방식으로 폴백
                const modal = document.getElementById('createOrganizationModal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
        },

        // 프로젝트 로드 (from projectManager)
        async loadProjects(orgId) {
            try {
                this.isLoading = true;
                // TODO: 실제 API 구현 시 ApiClient 사용
                await new Promise(resolve => setTimeout(resolve, 500));
                this.projects = [];
                console.log(`조직 ${orgId}의 프로젝트 목록을 로드했습니다.`, this.projects);
            } catch (error) {
                console.error('프로젝트 로드 실패:', error);
                if (window.ApiErrorHandler) {
                    window.ApiErrorHandler.handle(error, '프로젝트 로드');
                }
                this.projects = [];
            } finally {
                this.isLoading = false;
            }
        },

        // 프로젝트 상태 체크 (from projectManager)
        get hasProjects() {
            return this.projects.length > 0;
        }
    }
}

// Alpine.js 컴포넌트 등록 - 즉시 실행
(function() {
    function registerComponent() {
        if (window.Alpine) {
            Alpine.data('dashboardMain', dashboardMain);
        } else {
            setTimeout(registerComponent, 10);
        }
    }
    registerComponent();
})();

// 또한 alpine:init 이벤트에도 등록
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardMain', dashboardMain);
});
</script>