{{-- 모달 UI 관리자 --}}
<script>
/**
 * 모달 UI 관리를 담당하는 클래스
 * 기능: 모달 표시/숨김, 애니메이션, 상태 관리
 */
class ModalUIManager {
    constructor() {
        this.activeModals = new Set();
    }

    // 모달 표시
    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`모달을 찾을 수 없습니다: ${modalId}`);
            return false;
        }

        modal.classList.remove('hidden');
        this.activeModals.add(modalId);
        
        // 접근성: 포커스 트랩 설정
        this.setFocusTrap(modal);
        
        console.log(`모달 표시: ${modalId}`);
        return true;
    }

    // 모달 숨기기
    hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.warn(`모달을 찾을 수 없습니다: ${modalId}`);
            return false;
        }

        modal.classList.add('hidden');
        this.activeModals.delete(modalId);
        
        // 포커스 복원
        this.restoreFocus();
        
        console.log(`모달 숨김: ${modalId}`);
        return true;
    }

    // 모든 모달 숨기기
    hideAllModals() {
        this.activeModals.forEach(modalId => {
            this.hideModal(modalId);
        });
    }

    // 모달 상태 확인
    isModalVisible(modalId) {
        return this.activeModals.has(modalId);
    }

    // 활성 모달 목록
    getActiveModals() {
        return Array.from(this.activeModals);
    }

    // 포커스 트랩 설정 (접근성)
    setFocusTrap(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
    }

    // 포커스 복원
    restoreFocus() {
        // 이전에 포커스된 요소로 복원하는 로직 추가 가능
        document.body.focus();
    }

    // ESC 키로 모달 닫기 이벤트 설정
    setupGlobalEvents() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModals.size > 0) {
                // 가장 최근에 열린 모달 닫기
                const lastModal = Array.from(this.activeModals).pop();
                this.hideModal(lastModal);
            }
        });
    }

    // 조직 생성 모달 관련 메서드 (하위 호환성)
    showCreateOrganizationModal() {
        return this.showModal('createOrganizationModal');
    }

    hideCreateOrganizationModal() {
        return this.hideModal('createOrganizationModal');
    }
}

// 전역 모달 UI 매니저 인스턴스
window.modalUI = new ModalUIManager();

// 글로벌 이벤트 설정
document.addEventListener('DOMContentLoaded', () => {
    window.modalUI.setupGlobalEvents();
});
</script>