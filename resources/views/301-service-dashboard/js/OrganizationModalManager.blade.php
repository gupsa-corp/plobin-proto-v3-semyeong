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
        this.urlPathInput = null;
        this.createSubmitBtn = null;
        this.urlCheckTimeout = null;
        this.lastCheckedUrl = '';
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
            this.urlPathInput = document.getElementById('urlPath');

            if (this.orgNameInput) this.orgNameInput.value = '';
            if (this.urlPathInput) {
                this.urlPathInput.value = '';
                this.clearUrlCheckStatus();
            }
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
     * @param {string} urlPath - URL 명
     */
    showSuccessModal(orgName, urlPath) {
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
                organizationUrl.textContent = `www.plobin.com/orgs/${urlPath}`;
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
     * URL 중복 확인 상태를 지웁니다
     */
    clearUrlCheckStatus() {
        const statusElement = document.getElementById('urlCheckStatus');
        const urlPathInput = document.getElementById('urlPath');
        
        if (statusElement) {
            statusElement.textContent = '';
            statusElement.className = 'text-xs';
        }
        
        if (urlPathInput) {
            urlPathInput.className = 'w-full px-3 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500';
        }
    }

    /**
     * URL 중복 확인 상태를 업데이트합니다
     * @param {string} status - 'checking', 'available', 'unavailable', 'error'
     * @param {string} message - 상태 메시지
     */
    updateUrlCheckStatus(status, message = '') {
        const statusElement = document.getElementById('urlCheckStatus');
        const urlPathInput = document.getElementById('urlPath');
        
        if (!statusElement || !urlPathInput) return;
        
        statusElement.textContent = message;
        
        switch (status) {
            case 'checking':
                statusElement.className = 'text-xs text-blue-500';
                urlPathInput.className = 'w-full px-3 py-3 border border-blue-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
                break;
            case 'available':
                statusElement.className = 'text-xs text-green-600';
                urlPathInput.className = 'w-full px-3 py-3 border border-green-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500';
                break;
            case 'unavailable':
                statusElement.className = 'text-xs text-red-600';
                urlPathInput.className = 'w-full px-3 py-3 border border-red-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500';
                break;
            case 'error':
                statusElement.className = 'text-xs text-gray-500';
                urlPathInput.className = 'w-full px-3 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500';
                break;
        }
    }

    /**
     * URL 중복을 확인합니다
     * @param {string} urlPath - 확인할 URL 경로
     */
    async checkUrlAvailability(urlPath) {
        // 형식 검증
        const urlPathPattern = /^[a-z]{3,12}$/;
        if (!urlPathPattern.test(urlPath)) {
            this.clearUrlCheckStatus();
            return;
        }

        // 같은 URL을 다시 확인하지 않음
        if (urlPath === this.lastCheckedUrl) {
            return;
        }

        this.lastCheckedUrl = urlPath;
        this.updateUrlCheckStatus('checking', '확인 중...');

        try {
            const token = localStorage.getItem('auth_token');
            
            const response = await fetch(`/api/organizations/check-url/${urlPath}`, {
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
            
            if (data.success && data.data) {
                if (data.data.available) {
                    this.updateUrlCheckStatus('available', '✓ 사용 가능');
                } else {
                    this.updateUrlCheckStatus('unavailable', '✗ 이미 사용 중');
                }
            } else {
                this.updateUrlCheckStatus('error', '확인 실패');
            }

        } catch (error) {
            console.error('URL 중복 확인 오류:', error);
            this.updateUrlCheckStatus('error', '확인 실패');
        }
    }

    /**
     * URL 입력 필드에 디바운싱된 중복 확인을 설정합니다
     */
    setupUrlValidation() {
        const urlPathInput = document.getElementById('urlPath');
        if (!urlPathInput) return;

        urlPathInput.addEventListener('input', (e) => {
            const urlPath = e.target.value.trim();
            
            // 이전 타이머 취소
            if (this.urlCheckTimeout) {
                clearTimeout(this.urlCheckTimeout);
            }
            
            // 빈 값이면 상태 초기화
            if (!urlPath) {
                this.clearUrlCheckStatus();
                this.lastCheckedUrl = '';
                return;
            }
            
            // 500ms 후 중복 확인 실행
            this.urlCheckTimeout = setTimeout(() => {
                this.checkUrlAvailability(urlPath);
            }, 500);
        });
    }

    /**
     * 조직을 생성합니다
     */
    async createOrganization() {
        // 입력 필드를 다시 참조 (모달이 열린 후 변경될 수 있으므로)
        this.orgNameInput = document.getElementById('orgName');
        this.urlPathInput = document.getElementById('urlPath');

        const orgName = this.orgNameInput?.value.trim();
        const urlPath = this.urlPathInput?.value.trim();
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

        if (!urlPath) {
            alert('URL 명을 입력해주세요.');
            return;
        }

        const urlPathPattern = /^[a-z]{3,12}$/;
        if (!urlPathPattern.test(urlPath)) {
            alert('URL 명은 영문 소문자 3~12자로 입력해주세요.');
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
                    url_path: urlPath,
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
            this.showSuccessModal(orgName, urlPath);

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
                } else if (error.message.includes('409') || error.message.includes('conflict') || error.message.includes('unique')) {
                    errorMessage = '이미 사용 중인 URL 명입니다. 다른 URL 명을 사용해주세요.';
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
            createOrgBtn.addEventListener('click', () => {
                this.showCreateModal();
                // 모달이 열린 후 URL 유효성 검사 설정
                setTimeout(() => this.setupUrlValidation(), 100);
            });
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
