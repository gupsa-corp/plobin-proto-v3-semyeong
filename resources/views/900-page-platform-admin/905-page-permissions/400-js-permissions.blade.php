{{-- 권한 관리 페이지 JavaScript --}}
<script>
// 탭 전환 기능
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 모든 탭 비활성화
            tabLinks.forEach(l => {
                l.classList.remove('border-indigo-500', 'text-indigo-600', 'active');
                l.classList.add('border-transparent', 'text-gray-500');
            });
            
            // 모든 탭 콘텐츠 숨기기
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // 선택된 탭 활성화
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-indigo-500', 'text-indigo-600', 'active');
            
            // 해당 탭 콘텐츠 표시
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').classList.remove('hidden');
        });
    });
});

// 모달 관련 함수
function openCreateRoleModal() {
    document.getElementById('create-role-modal').classList.remove('hidden');
}

function closeCreateRoleModal() {
    document.getElementById('create-role-modal').classList.add('hidden');
}

// 모달 외부 클릭시 닫기
document.addEventListener('click', function(event) {
    const modal = document.getElementById('create-role-modal');
    if (event.target === modal) {
        closeCreateRoleModal();
    }
});

// ESC 키로 모달 닫기
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateRoleModal();
    }
});
</script>