<!-- 관리자 설정 버튼 -->
<div class="relative">
    <button type="button" 
            class="flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
            id="adminSettingsButton"
            title="관리자 설정">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </button>
    
    <!-- 드롭다운 메뉴 -->
    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden" 
         id="adminSettingsDropdown">
        <a href="/admin/users" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            사용자 관리
        </a>
        <a href="/admin/settings" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            시스템 설정
        </a>
        <a href="/admin/logs" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            로그 관리
        </a>
        <div class="border-t border-gray-100"></div>
        <a href="/admin/backup" 
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            백업 관리
        </a>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const adminButton = document.getElementById('adminSettingsButton');
    const adminDropdown = document.getElementById('adminSettingsDropdown');
    
    if (adminButton && adminDropdown) {
        adminButton.addEventListener('click', function(e) {
            e.stopPropagation();
            adminDropdown.classList.toggle('hidden');
        });
        
        // 외부 클릭시 드롭다운 닫기
        document.addEventListener('click', function() {
            adminDropdown.classList.add('hidden');
        });
        
        // 드롭다운 내부 클릭시 이벤트 전파 방지
        adminDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>