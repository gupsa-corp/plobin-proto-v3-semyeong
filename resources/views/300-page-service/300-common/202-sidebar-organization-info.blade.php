<!-- 조직 선택 영역 -->
<div class="organization-section" style="padding: 16px 20px; position: relative;">
    <div class="org-selector" id="orgSelector" style="display: flex; align-items: center; padding: 10px 12px; background: #E9E9ED; border: 0.5px solid #E1E1E4; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
        <div class="org-icon" style="width: 28px; height: 28px; background: #ffffff; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
            <div class="chart-icon" style="width: 20px; height: 20px; background: #0DC8AF; border-radius: 2px; position: relative;"></div>
        </div>
        <span class="org-text" style="flex: 1; font-size: 14px; color: #666666;">{{ $orgConfig['no_org_text'] }}</span>
        <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" style="color: #666666; transition: transform 0.2s ease;">
            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" fill="none" stroke-width="1.5"/>
        </svg>
    </div>

    <!-- 조직 드롭다운 메뉴 -->
    <div class="org-dropdown" id="orgDropdown" style="position: absolute; top: 100%; left: 20px; right: 20px; background: #ffffff; border: 1px solid #E1E1E4; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); z-index: 50; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s ease;">
        <div class="org-dropdown-header" style="padding: 12px; border-bottom: 1px solid #E1E1E4;">
            <input type="text" placeholder="{{ $orgConfig['search_placeholder'] }}" class="org-search" id="orgSearch" style="width: 100%; padding: 8px 12px; border: 1px solid #E1E1E4; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box;">
        </div>
        <div class="org-list" id="orgList" style="max-height: 200px; overflow-y: auto;">
            <!-- 조직이 없을 때 표시할 메시지 -->
            <div class="no-org-message" style="padding: 20px; text-align: center; color: #666666; font-size: 14px;">
                {{ $orgConfig['no_org_message'] }}<br>
                <span style="font-size: 12px; color: #888888;">{{ $orgConfig['no_org_submessage'] }}</span>
            </div>
        </div>
        <div class="org-actions" style="padding: 12px; border-top: 1px solid #E1E1E4;">
            <button class="create-org-btn" id="createOrgBtn" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 12px; background: transparent; border: 1px solid #E1E1E4; border-radius: 6px; font-size: 12px; color: #111111; cursor: pointer; transition: all 0.2s ease;">
                <svg width="16" height="16" viewBox="0 0 16 16">
                    <path d="M8 3v10M3 8h10" stroke="currentColor" fill="none" stroke-width="1.5"/>
                </svg>
                {{ $orgConfig['create_org_button_text'] }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orgSelector = document.getElementById('orgSelector');
    const orgDropdown = document.getElementById('orgDropdown');
    const dropdownArrow = document.querySelector('.dropdown-arrow');
    const orgSearch = document.getElementById('orgSearch');
    const createOrgBtn = document.getElementById('createOrgBtn');

    // 드롭다운 토글 기능
    orgSelector.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleDropdown();
    });

    // 드롭다운 토글 함수
    function toggleDropdown() {
        const isVisible = orgDropdown.style.opacity === '1';

        if (isVisible) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }

    // 드롭다운 열기
    function openDropdown() {
        orgDropdown.style.opacity = '1';
        orgDropdown.style.visibility = 'visible';
        orgDropdown.style.transform = 'translateY(0)';
        dropdownArrow.style.transform = 'rotate(180deg)';

        // 검색 입력창에 포커스
        setTimeout(() => {
            orgSearch.focus();
        }, 100);
    }

    // 드롭다운 닫기
    function closeDropdown() {
        orgDropdown.style.opacity = '0';
        orgDropdown.style.visibility = 'hidden';
        orgDropdown.style.transform = 'translateY(-10px)';
        dropdownArrow.style.transform = 'rotate(0deg)';

        // 검색 입력창 초기화
        orgSearch.value = '';
    }

    // 외부 클릭시 드롭다운 닫기
    document.addEventListener('click', function(e) {
        if (!orgSelector.contains(e.target) && !orgDropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    // 검색 기능
    orgSearch.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        // 여기에 조직 검색 로직 추가
        console.log('조직 검색:', searchTerm);
    });

    // 새 조직 만들기 버튼
    createOrgBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        console.log('새 조직 만들기');
        // 새 조직 만들기 모달이나 페이지로 이동
        // window.location.href = '/organization/create';
    });

    // 버튼 호버 효과
    createOrgBtn.addEventListener('mouseenter', function() {
        this.style.background = '#F3F4F6';
        this.style.borderColor = '#D1D5DB';
    });

    createOrgBtn.addEventListener('mouseleave', function() {
        this.style.background = 'transparent';
        this.style.borderColor = '#E1E1E4';
    });
});
</script>