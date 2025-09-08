{{-- 공통 JavaScript 모듈 --}}
<script>
// 공통 유틸리티 함수
window.utils = {
    // 공통 유틸리티를 여기에 추가
};

// Alpine.js 초기화
document.addEventListener('alpine:init', () => {
    // 전역 Alpine.js 설정
});

// Livewire 인증 만료 감지
document.addEventListener('livewire:response-received', (event) => {
    const response = event.detail.xhr.response;
    if (response && typeof response === 'string') {
        try {
            const data = JSON.parse(response);
            if (data.auth_expired) {
                // 로그인 모달 표시
                window.$dispatch('show-login-modal');
            }
        } catch (e) {
            // JSON 파싱 실패 시 무시
        }
    }
});
</script>