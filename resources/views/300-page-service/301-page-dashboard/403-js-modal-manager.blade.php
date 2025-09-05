{{-- 모달 관리 --}}
<script>
/**
 * 조직 생성 모달 표시/숨김을 담당하는 함수
 * 기능: 모달 열기, 모달 닫기, 모달 상태 관리
 */
function modalManager() {
    return {
        // 조직 생성 모달 표시
        showCreateOrganizationModal() {
            const modal = document.getElementById('createOrganizationModal');
            if (modal) {
                modal.classList.remove('hidden');
                console.log('조직 생성 모달을 표시했습니다.');
            } else {
                console.error('조직 생성 모달을 찾을 수 없습니다.');
            }
        },

        // 조직 생성 모달 숨기기
        hideCreateOrganizationModal() {
            const modal = document.getElementById('createOrganizationModal');
            if (modal) {
                modal.classList.add('hidden');
                console.log('조직 생성 모달을 숨겼습니다.');
            }
        },

        // 모달 닫기 이벤트 설정
        setupModalEvents() {
            // 닫기 버튼 이벤트
            const closeBtn = document.getElementById('closeModalBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    this.hideCreateOrganizationModal();
                });
            }

            // 배경 클릭 시 닫기
            const modal = document.getElementById('createOrganizationModal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.hideCreateOrganizationModal();
                    }
                });
            }
        }
    };
}

// 전역 객체로 등록 및 이벤트 설정
document.addEventListener('DOMContentLoaded', () => {
    window.modalManager = modalManager();
    window.modalManager.setupModalEvents();
});
</script>