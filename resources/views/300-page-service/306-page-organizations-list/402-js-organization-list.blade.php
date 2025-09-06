{{-- 조직 목록 관리 --}}
<script>
/**
 * 조직 목록 로드 및 렌더링을 담당하는 함수
 * 기능: 조직 목록 API 호출, 조직 카드 렌더링, 조직 선택 처리
 */
function organizationListManager() {
    return {
        isLoading: false,

        async loadOrganizations() {
            try {
                this.isLoading = true;
                // TODO: 실제 API 호출로 조직 목록 가져오기
                // const response = await ApiClient.get('/api/organizations');
                // const organizations = response.data?.organizations || [];
                
                // 임시 데이터
                const organizations = [];
                
                this.renderOrganizations(organizations);
            } catch (error) {
                console.error('조직 목록 로드 실패:', error);
            } finally {
                this.isLoading = false;
            }
        },

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
                
                // 버튼 이벤트 추가
                const createBtn = document.getElementById('createOrganizationBtnInList');
                if (createBtn) {
                    createBtn.addEventListener('click', () => {
                        window.modalManager?.showCreateOrganizationModal();
                    });
                }
            } else {
                orgList.innerHTML = organizations.map(org => `
                    <div class="organization-card p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer"
                         onclick="window.organizationList.selectOrganization('${org.id}')">
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
            }
        },

        selectOrganization(orgId) {
            const orgData = { id: orgId };
            localStorage.setItem('selectedOrg', JSON.stringify(orgData));
            
            // 조직 대시보드로 이동
            window.location.href = `/organizations/${orgId}/dashboard`;
        }
    };
}

// 전역 객체로 등록
document.addEventListener('DOMContentLoaded', () => {
    window.organizationList = organizationListManager();
});
</script>