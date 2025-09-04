{{-- 조직 모달 관리 클래스 --}}
<script>
/**
 * 조직 생성 모달을 관리하는 클래스
 */
class OrganizationModalManager {
    constructor() {
        this.createModal = null;
        this.successModal = null;
        this.orgNameInput = null;
        this.subdomainInput = null;
        this.checkDuplicateBtn = null;
        this.createSubmitBtn = null;
    }

    /**
     * 조직 생성 모달을 표시합니다
     */
    showCreateModal() {
        this.createModal = document.getElementById('createOrganizationModal');
        if (this.createModal) {
            this.createModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // 입력 필드 초기화
            this.orgNameInput = document.getElementById('orgName');
            this.subdomainInput = document.getElementById('subdomain');
            
            if (this.orgNameInput) this.orgNameInput.value = '';
            if (this.subdomainInput) this.subdomainInput.value = '';
        }
    }

    /**
     * 조직 생성 모달을 숨깁니다
     */
    hideCreateModal() {
        if (this.createModal) {
            this.createModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * 성공 모달을 표시합니다
     * @param {string} orgName - 조직명
     * @param {string} subdomain - 하위 도메인
     */
    showSuccessModal(orgName, subdomain) {
        this.successModal = document.getElementById('createOrganizationSuccessModal');
        if (this.successModal) {
            // 제목 업데이트
            const successTitle = document.getElementById('successTitle');
            if (successTitle) {
                successTitle.textContent = `${orgName} 조직이 생성되었습니다`;
            }

            // URL 업데이트
            const organizationUrl = document.getElementById('organizationUrl');
            if (organizationUrl) {
                organizationUrl.textContent = `www.plobin.com/orgs/${subdomain}`;
            }

            this.successModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * 성공 모달을 숨깁니다
     */
    hideSuccessModal() {
        if (this.successModal) {
            this.successModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * 도메인 중복을 확인합니다
     */
    async checkDuplicate() {
        const subdomain = this.subdomainInput?.value.trim();
        this.checkDuplicateBtn = document.getElementById('checkDuplicateBtn');
        
        if (!subdomain) {
            alert('하위 도메인을 입력해주세요.');
            return;
        }

        // 영문 소문자 3~12자 유효성 검사
        const subdomainPattern = /^[a-z]{3,12}$/;
        if (!subdomainPattern.test(subdomain)) {
            alert('하위 도메인은 영문 소문자 3~12자로 입력해주세요.');
            return;
        }

        // 버튼 비활성화 및 로딩 상태
        if (this.checkDuplicateBtn) {
            this.checkDuplicateBtn.disabled = true;
            this.checkDuplicateBtn.textContent = '확인중...';
        }

        try {
            const token = localStorage.getItem('auth_token');

            // 실제 API 호출 (중복 확인을 위한 별도 엔드포인트가 필요할 수 있음)
            // 현재는 조직 목록을 가져와서 중복 체크
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
            const organizations = data.data || data.organizations || data || [];
            
            // 현재 조직 목록에서 같은 subdomain이 있는지 확인
            const isDuplicate = organizations.some(org => 
                (org.code === subdomain) || 
                (org.subdomain === subdomain) || 
                (org.slug === subdomain)
            );

            if (isDuplicate) {
                alert('이미 사용 중인 도메인입니다. 다른 도메인을 입력해주세요.');
            } else {
                alert('사용 가능한 도메인입니다.');
            }
            
        } catch (error) {
            ApiErrorHandler.handle(error, '중복 확인');
            
            // 401이 아닌 경우에만 사용자 알림 및 버튼 복원
            if (!ApiErrorHandler.is401Error(error)) {
                alert('중복 확인 중 오류가 발생했습니다. 다시 시도해주세요.');
                if (this.checkDuplicateBtn) {
                    this.checkDuplicateBtn.disabled = false;
                    this.checkDuplicateBtn.textContent = '중복확인';
                }
            }
        }
    }

    /**
     * 조직을 생성합니다
     */
    async createOrganization() {
        const orgName = this.orgNameInput?.value.trim();
        const subdomain = this.subdomainInput?.value.trim();
        this.createSubmitBtn = document.getElementById('createOrgSubmitBtn');
        
        // 유효성 검사
        if (!orgName) {
            alert('조직 이름을 입력해주세요.');
            return;
        }

        if (orgName.length < 1 || orgName.length > 25) {
            alert('조직 이름은 1~25자로 입력해주세요.');
            return;
        }

        if (!subdomain) {
            alert('하위 도메인을 입력해주세요.');
            return;
        }

        const subdomainPattern = /^[a-z]{3,12}$/;
        if (!subdomainPattern.test(subdomain)) {
            alert('하위 도메인은 영문 소문자 3~12자로 입력해주세요.');
            return;
        }

        // 버튼 비활성화 및 로딩 상태
        if (this.createSubmitBtn) {
            this.createSubmitBtn.disabled = true;
            this.createSubmitBtn.textContent = '생성 중...';
        }

        try {
            const token = localStorage.getItem('auth_token');

            // 실제 API 호출
            const response = await fetch('/api/organizations/create', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: orgName,
                    subdomain: subdomain,
                    // 필요한 경우 추가 필드들
                    description: '', // 빈 설명
                    type: 'organization' // 기본 타입
                })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                const errorMessage = errorData.message || `API 오류: ${response.status}`;
                throw new Error(errorMessage);
            }

            const data = await response.json();
            console.log('조직 생성 성공:', data);
            
            // 생성 모달 닫기
            this.hideCreateModal();
            
            // 성공 모달 표시
            this.showSuccessModal(orgName, subdomain);
            
            // 조직 목록 다시 로드
            const organizationManager = new OrganizationManager();
            organizationManager.loadOrganizations();
            
        } catch (error) {
            ApiErrorHandler.handle(error, '조직 생성');
            
            // 401이 아닌 경우에만 사용자 알림 및 버튼 복원
            if (!ApiErrorHandler.is401Error(error)) {
                // 에러 메시지 표시
                let errorMessage = '조직 생성 중 오류가 발생했습니다.';
                
                if (error.message.includes('422')) {
                    errorMessage = '입력한 정보를 다시 확인해주세요.';
                } else if (error.message.includes('409') || error.message.includes('conflict')) {
                    errorMessage = '이미 존재하는 도메인입니다. 다른 도메인을 사용해주세요.';
                }
                
                alert(errorMessage);
                
                if (this.createSubmitBtn) {
                    this.createSubmitBtn.disabled = false;
                    this.createSubmitBtn.textContent = '생성하기';
                }
            }
        }
    }

    /**
     * 모달 이벤트 리스너를 설정합니다
     */
    setupEventListeners() {
        // 새 조직 생성 버튼
        const createOrgBtn = document.getElementById('createOrganizationBtn');
        if (createOrgBtn) {
            createOrgBtn.addEventListener('click', () => this.showCreateModal());
        }

        // 모달 닫기 버튼
        const closeModalBtn = document.getElementById('closeModalBtn');
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => this.hideCreateModal());
        }

        // 모달 배경 클릭시 닫기
        const modal = document.getElementById('createOrganizationModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.hideCreateModal();
                }
            });
        }

        // 중복 확인 버튼
        const checkDuplicateBtn = document.getElementById('checkDuplicateBtn');
        if (checkDuplicateBtn) {
            checkDuplicateBtn.addEventListener('click', () => this.checkDuplicate());
        }

        // 생성하기 버튼
        const createOrgSubmitBtn = document.getElementById('createOrgSubmitBtn');
        if (createOrgSubmitBtn) {
            createOrgSubmitBtn.addEventListener('click', () => this.createOrganization());
        }

        // 성공 모달 닫기 버튼
        const closeSuccessModalBtn = document.getElementById('closeSuccessModalBtn');
        if (closeSuccessModalBtn) {
            closeSuccessModalBtn.addEventListener('click', () => this.hideSuccessModal());
        }

        // 성공 모달 확인 버튼
        const successConfirmBtn = document.getElementById('successConfirmBtn');
        if (successConfirmBtn) {
            successConfirmBtn.addEventListener('click', () => this.hideSuccessModal());
        }

        // 성공 모달 배경 클릭시 닫기
        const successModal = document.getElementById('createOrganizationSuccessModal');
        if (successModal) {
            successModal.addEventListener('click', (e) => {
                if (e.target === successModal) {
                    this.hideSuccessModal();
                }
            });
        }

        // ESC 키로 모달 닫기
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const createModal = document.getElementById('createOrganizationModal');
                const successModal = document.getElementById('createOrganizationSuccessModal');
                
                if (createModal && !createModal.classList.contains('hidden')) {
                    this.hideCreateModal();
                } else if (successModal && !successModal.classList.contains('hidden')) {
                    this.hideSuccessModal();
                }
            }
        });
    }
}
</script>