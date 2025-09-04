{{-- 조직 관리 클래스 --}}
<script>
/**
 * 조직 관리를 담당하는 클래스
 */
class OrganizationManager {
    constructor() {
        this.organizations = [];
        this.organizationListElement = null;
    }

    /**
     * 조직 목록을 로드합니다
     */
    async loadOrganizations() {
        try {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                console.error('토큰이 없습니다.');
                this.showEmptyOrganizations();
                return;
            }

            const response = await fetch('/api/organizations/list', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`API 오류: ${response.status}`);
            }

            const data = await response.json();
            this.organizations = data.data || data.organizations || data;

            this.organizationListElement = document.getElementById('organizationList');
            this.organizationListElement.innerHTML = '';

            if (!this.organizations || this.organizations.length === 0) {
                this.showEmptyOrganizations();
                return;
            }

            this.organizations.forEach(org => {
                const orgElement = this.createOrganizationElement(org);
                this.organizationListElement.appendChild(orgElement);
            });

        } catch (error) {
            ApiErrorHandler.handle(error, '조직 목록 로드');
            
            // 401이 아닌 다른 오류의 경우 빈 상태 표시
            if (!ApiErrorHandler.is401Error(error)) {
                this.showEmptyOrganizations();
            }
        }
    }

    /**
     * 빈 조직 목록을 표시합니다
     */
    showEmptyOrganizations() {
        const organizationList = document.getElementById('organizationList');
        organizationList.innerHTML = '';

        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'bg-white border border-gray-200 rounded-lg shadow-sm p-5 flex flex-col justify-center items-start gap-2 w-[362px] h-[180px]';
        
        emptyDiv.innerHTML = `
            <div class="flex flex-col items-start gap-5 w-full h-full pr-5">
                <div class="flex flex-col items-start gap-1 w-full">
                    <div class="flex items-center justify-center pt-2 pl-1 gap-2 w-full h-[60px]">
                        <p class="text-lg text-gray-900 leading-[26px] flex-1">
                            현재 소속된 조직이 없습니다.<br>초대를 받거나, 조직을 생성해주세요.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center justify-center">
                    <button id="emptyCreateOrgBtn" class="flex items-center justify-center gap-1 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white font-bold text-sm rounded-full h-[42px]">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M15.625 9.375H10.625V4.375C10.625 4.02982 10.3452 3.75 10 3.75C9.65482 3.75 9.375 4.02982 9.375 4.375V9.375H4.375C4.02982 9.375 3.75 9.65482 3.75 10C3.75 10.3452 4.02982 10.625 4.375 10.625H9.375V15.625C9.375 15.9702 9.65482 16.25 10 16.25C10.3452 16.25 10.625 15.9702 10.625 15.625V10.625H15.625C15.9702 10.625 16.25 10.3452 16.25 10C16.25 9.65482 15.9702 9.375 15.625 9.375Z" fill="white"/>
                        </svg>
                        새조직 생성하기
                    </button>
                </div>
            </div>
        `;

        organizationList.appendChild(emptyDiv);

        // 빈 상태의 조직 생성 버튼 이벤트 추가
        const emptyCreateOrgBtn = document.getElementById('emptyCreateOrgBtn');
        if (emptyCreateOrgBtn) {
            emptyCreateOrgBtn.addEventListener('click', () => {
                const modalManager = new OrganizationModalManager();
                modalManager.showCreateModal();
            });
        }
    }

    /**
     * 조직 카드 엘리먼트를 생성합니다
     * @param {Object} org - 조직 데이터
     * @returns {HTMLElement} 생성된 조직 카드 엘리먼트
     */
    createOrganizationElement(org) {
        const div = document.createElement('div');
        div.className = 'bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 cursor-pointer';
        div.onclick = () => this.selectOrganization(org.id);

        // 조직 아바타 색상 설정 (서버에서 제공하지 않는 경우 기본값 사용)
        const avatarColor = org.avatar_color || org.color || '#0DC8AF';
        // 조직 아바타 텍스트 (이름의 첫 글자)
        const avatarText = org.avatar || org.name?.charAt(0)?.toUpperCase() || '?';
        // 조직 코드 (서버 응답 구조에 맞게 조정)
        const orgCode = org.code || org.subdomain || org.slug || 'no-code';

        div.innerHTML = `
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-2xl" style="background-color: ${avatarColor}">
                        ${avatarText}
                    </div>
                    <div class="relative">
                        <button class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full" onclick="event.stopPropagation(); this.showOrganizationMenu(${org.id})">
                            <div class="flex flex-col gap-1">
                                <div class="w-1 h-1 bg-gray-800 rounded-full"></div>
                                <div class="w-1 h-1 bg-gray-800 rounded-full"></div>
                                <div class="w-1 h-1 bg-gray-800 rounded-full"></div>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">${org.name}</h3>
                    <div class="flex items-center text-gray-500 text-base">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="mr-2">
                            <path d="M8 8V6C8 3.79086 9.79086 2 12 2C14.2091 2 16 3.79086 16 6V8M8 8H16M8 8H6C4.89543 8 4 8.89543 4 10V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V10C20 8.89543 19.1046 8 18 8H16" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        ${orgCode}
                    </div>
                </div>
            </div>
        `;

        return div;
    }

    /**
     * 조직을 선택합니다
     * @param {number} orgId - 조직 ID
     */
    selectOrganization(orgId) {
        console.log('조직 선택:', orgId);
        // TODO: 조직 선택 처리 로직
    }

    /**
     * 조직 메뉴를 표시합니다
     * @param {number} orgId - 조직 ID
     */
    showOrganizationMenu(orgId) {
        console.log('조직 메뉴 표시:', orgId);
        // TODO: 조직 메뉴 처리 로직
    }
}
</script>