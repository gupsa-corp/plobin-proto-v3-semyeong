{{-- 모달 관리 공통 유틸리티 --}}
<script>
/**
 * 모달 관리를 위한 공통 유틸리티
 */
class ModalUtils {
    /**
     * 모달을 표시합니다
     * @param {string} modalId - 모달 ID
     */
    static showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * 모달을 숨깁니다
     * @param {string} modalId - 모달 ID
     */
    static hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * 모달 외부 클릭 시 닫기 이벤트를 설정합니다
     * @param {string} modalId - 모달 ID
     */
    static setupBackdropClose(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.hideModal(modalId);
                }
            });
        }
    }

    /**
     * ESC 키로 모달 닫기 이벤트를 설정합니다
     * @param {string|Array} modalIds - 모달 ID 또는 모달 ID 배열
     */
    static setupEscapeClose(modalIds) {
        const ids = Array.isArray(modalIds) ? modalIds : [modalIds];
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                for (const modalId of ids) {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        this.hideModal(modalId);
                        break; // 첫 번째로 열린 모달만 닫기
                    }
                }
            }
        });
    }

    /**
     * 버튼 클릭으로 모달 열기/닫기 이벤트를 설정합니다
     * @param {string} buttonId - 버튼 ID
     * @param {string} modalId - 모달 ID
     * @param {string} action - 'show' 또는 'hide'
     */
    static setupButtonToggle(buttonId, modalId, action = 'show') {
        const button = document.getElementById(buttonId);
        if (button) {
            button.addEventListener('click', () => {
                if (action === 'show') {
                    this.showModal(modalId);
                } else {
                    this.hideModal(modalId);
                }
            });
        }
    }

    /**
     * 모달 내 입력 필드를 초기화합니다
     * @param {string} modalId - 모달 ID
     */
    static clearModalInputs(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const inputs = modal.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
        }
    }

    /**
     * 모달 내 텍스트 요소의 내용을 업데이트합니다
     * @param {string} elementId - 요소 ID
     * @param {string} text - 새로운 텍스트
     */
    static updateModalText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        }
    }
}
</script>