@php
    // 현재 URL에서 조직 ID 추출
    $currentOrgId = null;
    if (preg_match('/\/organizations\/(\d+)/', request()->getRequestUri(), $matches)) {
        $currentOrgId = (int) $matches[1];
    }
    
    // 현재 조직 정보 찾기
    $currentOrg = null;
    if ($currentOrgId && isset($organizations)) {
        $currentOrg = $organizations->firstWhere('id', $currentOrgId);
    }
    
    $orgConfig = [
        'current_org_name' => $currentOrg ? $currentOrg->name : '조직을 선택해주세요',
        'no_org_text' => '조직을 선택해주세요',
        'search_placeholder' => '조직 검색...',
        'no_org_message' => '조직이 없습니다',
        'no_org_submessage' => '새 조직을 만들어 시작해보세요',
        'create_org_button_text' => '조직 만들기'
    ];
@endphp

<!-- 조직 선택 영역 -->
<div class="organization-section" style="padding: 16px 20px; position: relative;">
    <div class="org-selector" id="orgSelector" style="display: flex; align-items: center; padding: 10px 12px; background: #E9E9ED; border: 0.5px solid #E1E1E4; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
        <div class="org-icon" style="width: 28px; height: 28px; background: #ffffff; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
            <div class="chart-icon" style="width: 20px; height: 20px; background: #0DC8AF; border-radius: 2px; position: relative;"></div>
        </div>
        <span class="org-text" style="flex: 1; font-size: 14px; color: #666666;">{{ $orgConfig['current_org_name'] }}</span>
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
    const orgList = document.getElementById('orgList');
    const orgText = document.querySelector('.org-text');

    // 서버에서 전달된 조직 데이터 사용
    @if(isset($organizations))
        let organizations = @json($organizations);
    @else
        let organizations = [];
    @endif
    
    let filteredOrganizations = [...organizations];
    let currentOrgId = {{ $currentOrgId ?? 'null' }};

    console.log('Organizations loaded:', organizations);
    console.log('Current org ID:', currentOrgId);

    // 조직 목록 렌더링
    function renderOrganizationList() {
        if (filteredOrganizations.length === 0) {
            orgList.innerHTML = `
                <div class="no-org-message" style="padding: 20px; text-align: center; color: #666666; font-size: 14px;">
                    조직이 없습니다<br>
                    <span style="font-size: 12px; color: #888888;">새 조직을 만들어 시작해보세요</span>
                </div>
            `;
            return;
        }

        const orgItems = filteredOrganizations.map(org => `
            <div class="org-item" data-org-id="${org.id}" style="display: flex; align-items: center; padding: 12px; cursor: pointer; border-bottom: 1px solid #F3F4F6; transition: background 0.2s ease;">
                <div class="org-icon" style="width: 32px; height: 32px; background: #ffffff; border: 1px solid #E1E1E4; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                    <div class="chart-icon" style="width: 20px; height: 20px; background: #0DC8AF; border-radius: 2px; position: relative;"></div>
                </div>
                <div class="org-info" style="flex: 1;">
                    <div class="org-name" style="font-size: 14px; font-weight: 500; color: #111111; margin-bottom: 2px;">${org.name}</div>
                    <div class="org-desc" style="font-size: 12px; color: #666666;">조직 ID: ${org.id}</div>
                </div>
                ${org.id === currentOrgId ? '<div class="check-icon" style="color: #0DC8AF;"><svg width="16" height="16" viewBox="0 0 16 16"><path d="M13.5 4.5L6 12L2.5 8.5" stroke="currentColor" fill="none" stroke-width="2"/></svg></div>' : ''}
            </div>
        `).join('');

        orgList.innerHTML = orgItems;

        // 조직 선택 이벤트 리스너 추가
        orgList.querySelectorAll('.org-item').forEach(item => {
            item.addEventListener('click', function() {
                const orgId = parseInt(this.dataset.orgId);
                selectOrganization(orgId);
            });

            item.addEventListener('mouseenter', function() {
                this.style.background = '#F9FAFB';
            });

            item.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
            });
        });
    }

    // 조직 선택
    function selectOrganization(orgId) {
        if (orgId !== currentOrgId) {
            // URL을 조직별 페이지로 변경
            const currentPath = window.location.pathname;
            let newPath;
            
            if (currentPath.includes('/organizations/')) {
                // 현재 조직 페이지에 있다면 조직 ID만 변경
                newPath = currentPath.replace(/\/organizations\/\d+/, `/organizations/${orgId}`);
            } else {
                // 조직 페이지가 아니라면 기본 대시보드로
                newPath = `/organizations/${orgId}/dashboard`;
            }
            
            window.location.href = newPath;
        }
        closeDropdown();
    }

    // 조직 검색
    function filterOrganizations(searchTerm) {
        searchTerm = searchTerm.toLowerCase();
        filteredOrganizations = organizations.filter(org => 
            org.name.toLowerCase().includes(searchTerm) ||
            org.id.toString().includes(searchTerm)
        );
        renderOrganizationList();
    }

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
        filteredOrganizations = [...organizations];
        renderOrganizationList();
    }

    // 외부 클릭시 드롭다운 닫기
    document.addEventListener('click', function(e) {
        if (!orgSelector.contains(e.target) && !orgDropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    // 검색 기능
    orgSearch.addEventListener('input', function(e) {
        const searchTerm = e.target.value;
        filterOrganizations(searchTerm);
    });

    // 새 조직 만들기 버튼
    createOrgBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        window.location.href = '/organizations/create';
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

    // 초기 조직 목록 렌더링
    renderOrganizationList();
});
</script>
