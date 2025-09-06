{{-- 이벤트 핸들러 --}}
<script>
/**
 * DOM 이벤트 처리를 담당하는 클래스
 * 기능: 이벤트 리스너 등록, 이벤트 위임, 이벤트 정리
 */
class EventHandler {
    constructor(modalUI, organizationManager) {
        this.modalUI = modalUI;
        this.organizationManager = organizationManager;
        this.eventListeners = new Map(); // 이벤트 리스너 추적용
    }

    // 이벤트 리스너 등록 (추적 가능한 방식)
    addEventListener(element, event, handler, options = {}) {
        if (!element) return false;

        const wrappedHandler = (e) => {
            try {
                handler(e);
            } catch (error) {
                console.error('이벤트 핸들러 오류:', error);
            }
        };

        element.addEventListener(event, wrappedHandler, options);
        
        // 추적을 위해 저장
        const key = `${element.id || 'anonymous'}_${event}`;
        if (!this.eventListeners.has(key)) {
            this.eventListeners.set(key, []);
        }
        this.eventListeners.get(key).push({
            element,
            event,
            handler: wrappedHandler,
            options
        });

        return true;
    }

    // 모달 관련 이벤트 설정
    setupModalEvents() {
        // 조직 생성 모달 닫기 버튼
        const closeBtn = document.getElementById('closeModalBtn');
        this.addEventListener(closeBtn, 'click', () => {
            this.modalUI.hideCreateOrganizationModal();
        });

        // 조직 생성 제출 버튼
        const submitBtn = document.getElementById('createOrgSubmitBtn');
        this.addEventListener(submitBtn, 'click', () => {
            this.organizationManager.createOrganization();
        });

        // 모달 배경 클릭 시 닫기
        const modal = document.getElementById('createOrganizationModal');
        this.addEventListener(modal, 'click', (e) => {
            if (e.target === modal) {
                this.modalUI.hideCreateOrganizationModal();
            }
        });

        // Enter 키로 조직 생성
        const nameInput = document.getElementById('orgName');
        this.addEventListener(nameInput, 'keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.organizationManager.createOrganization();
            }
        });

        console.log('모달 이벤트 설정 완료');
    }

    // 조직 관련 이벤트 설정
    setupOrganizationEvents() {
        // 조직 생성 버튼 (모달 열기)
        const createOrgBtn = document.getElementById('createNewOrganizationBtn');
        this.addEventListener(createOrgBtn, 'click', () => {
            this.modalUI.showCreateOrganizationModal();
        });

        // 조직 카드 클릭 이벤트 (이벤트 위임 사용)
        const organizationContainer = document.getElementById('organizationContainer');
        this.addEventListener(organizationContainer, 'click', (e) => {
            const orgCard = e.target.closest('[data-organization-id]');
            if (orgCard) {
                const orgId = orgCard.dataset.organizationId;
                this.handleOrganizationClick(orgId);
            }
        });

        console.log('조직 이벤트 설정 완료');
    }

    // 폼 관련 이벤트 설정
    setupFormEvents() {
        // 조직 이름 입력 필드 실시간 유효성 검증
        const nameInput = document.getElementById('orgName');
        this.addEventListener(nameInput, 'input', (e) => {
            this.handleOrganizationNameInput(e.target.value);
        });

        // 폼 제출 방지 (기본 동작 차단)
        const forms = document.querySelectorAll('form[data-prevent-submit]');
        forms.forEach(form => {
            this.addEventListener(form, 'submit', (e) => {
                e.preventDefault();
            });
        });

        console.log('폼 이벤트 설정 완료');
    }

    // 조직 클릭 처리
    handleOrganizationClick(orgId) {
        console.log(`조직 클릭: ${orgId}`);
        // 조직 상세 페이지로 이동하거나 추가 작업 수행
        // window.location.href = `/organizations/${orgId}`;
    }

    // 조직 이름 입력 처리 (실시간 유효성 검증)
    handleOrganizationNameInput(value) {
        const validation = this.organizationManager.validateOrganizationName(value);
        const nameInput = document.getElementById('orgName');
        const errorMsg = document.getElementById('orgNameError');

        if (!nameInput) return;

        // 시각적 피드백
        if (validation.isValid) {
            nameInput.classList.remove('border-red-500');
            nameInput.classList.add('border-green-500');
        } else if (value.trim().length > 0) {
            nameInput.classList.remove('border-green-500');
            nameInput.classList.add('border-red-500');
        } else {
            nameInput.classList.remove('border-red-500', 'border-green-500');
        }

        // 에러 메시지 표시
        if (errorMsg) {
            if (!validation.isValid && value.trim().length > 0) {
                errorMsg.textContent = validation.message;
                errorMsg.classList.remove('hidden');
            } else {
                errorMsg.classList.add('hidden');
            }
        }
    }

    // 모든 이벤트 설정
    setupAllEvents() {
        this.setupModalEvents();
        this.setupOrganizationEvents();
        this.setupFormEvents();
        
        console.log('모든 이벤트 설정 완료');
    }

    // 이벤트 리스너 정리 (필요시)
    cleanup() {
        this.eventListeners.forEach(listeners => {
            listeners.forEach(({ element, event, handler, options }) => {
                element.removeEventListener(event, handler, options);
            });
        });
        this.eventListeners.clear();
        console.log('이벤트 리스너 정리 완료');
    }
}

// 전역 이벤트 핸들러 설정
document.addEventListener('DOMContentLoaded', () => {
    window.eventHandler = new EventHandler(window.modalUI, window.organizationManager);
    window.eventHandler.setupAllEvents();
});

// 페이지 언로드 시 정리
window.addEventListener('beforeunload', () => {
    if (window.eventHandler) {
        window.eventHandler.cleanup();
    }
});
</script>