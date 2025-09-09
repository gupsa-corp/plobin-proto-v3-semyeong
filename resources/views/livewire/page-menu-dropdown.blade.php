{{-- Livewire 페이지 메뉴 드롭다운 컴포넌트 --}}
<div class="page-dropdown-container" 
     style="display: flex !important; position: relative; opacity: 1 !important; visibility: visible !important;">
    <button onclick="console.log('Button clicked for page:', {{ $page['id'] }}); toggleDropdown({{ $page['id'] }})"
            style="width: 24px; height: 24px; border: 1px solid #D1D5DB; border-radius: 4px; background: #F8F9FA !important; color: #495057 !important; font-size: 14px; font-weight: bold; line-height: 1; cursor: pointer; display: flex !important; align-items: center; justify-content: center; opacity: 1 !important; visibility: visible !important; z-index: 999; margin-left: 4px;"
            onmouseover="this.style.background='#E9ECEF'; this.style.borderColor='#ADB5BD';"
            onmouseout="this.style.background='#F8F9FA'; this.style.borderColor='#D1D5DB';"
            title="페이지 옵션 (ID: {{ $page['id'] }})">
        ⋮
    </button>
    
    <div id="dropdown-{{ $page['id'] }}" class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; z-index: 9999; margin-top: 4px; width: 160px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
            
            {{-- 이름 변경 --}}
            <button onclick="
                const newTitle = prompt('새로운 페이지 이름을 입력하세요:', '{{ addslashes($page['title']) }}');
                if (newTitle && newTitle.trim() !== '') {
                    @this.updatePageTitle({{ $page['id'] }}, newTitle.trim());
                    closeAllDropdowns();
                }
            " style="width: 100%; padding: 8px 12px; border: none; background: none; text-align: left; font-size: 13px; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 8px;"
                    onmouseover="this.style.background='#F9FAFB'"
                    onmouseout="this.style.background='white'">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                이름 변경
            </button>
            
            {{-- 하위 페이지 추가 --}}
            <button onclick="
                @this.addChildPage({{ $page['id'] }});
                closeAllDropdowns();
            " style="width: 100%; padding: 8px 12px; border: none; background: none; text-align: left; font-size: 13px; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 8px;"
                    onmouseover="this.style.background='#F9FAFB'"
                    onmouseout="this.style.background='white'">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                하위 페이지 추가
            </button>
            
            {{-- 구분선 --}}
            <div style="height: 1px; background: #E5E7EB; margin: 4px 0;"></div>
            
            {{-- 삭제 --}}
            <button onclick="
                if (confirm('{{ addslashes($page['title']) }} 페이지를 정말 삭제하시겠습니까?\\n\\n하위 페이지가 있는 경우 먼저 하위 페이지를 삭제해야 합니다.')) {
                    @this.deletePage({{ $page['id'] }});
                    closeAllDropdowns();
                }
            " style="width: 100%; padding: 8px 12px; border: none; background: none; text-align: left; font-size: 13px; color: #DC2626; cursor: pointer; display: flex; align-items: center; gap: 8px;"
                    onmouseover="this.style.background='#FEF2F2'"
                    onmouseout="this.style.background='white'">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                삭제
            </button>
        </div>
</div>