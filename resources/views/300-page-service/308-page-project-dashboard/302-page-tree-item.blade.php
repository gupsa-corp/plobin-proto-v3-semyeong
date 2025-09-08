{{-- 재귀적 페이지 트리 아이템 --}}
<div style="margin-left: {{ $level * 16 }}px;" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
    <div @if($editingPageId != $page['id']) @click="if(!$event.target.closest('.page-dropdown-{{ $page['id'] }}')) { window.location.href='{{ route('project.dashboard.page', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $page['id']]) }}' }" @endif
       style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}; border-radius: 6px; cursor: {{ $editingPageId == $page['id'] ? 'default' : 'pointer' }}; text-decoration: none; {{ $currentPage && $currentPage['id'] == $page['id'] ? 'border-left: 2px solid #10B981;' : '' }}"
         onmouseover="if({{ $editingPageId == $page['id'] ? 'false' : 'true' }}) { this.style.background='#F9FAFB'; const dropdown = document.querySelector('.page-dropdown-{{ $page['id'] }}'); if(dropdown) dropdown.style.display='flex'; }"
         onmouseout="if({{ $editingPageId == $page['id'] ? 'false' : 'true' }}) { this.style.background='{{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}'; const dropdown = document.querySelector('.page-dropdown-{{ $page['id'] }}'); if(dropdown) dropdown.style.display='none'; }">
        <div style="display: flex; align-items: center; gap: 8px;">
            @if($level > 0)
                <div style="width: 16px; height: 1px; background: #E5E7EB; margin-right: -8px;"></div>
            @endif

            <svg style="width: 16px; height: 16px; color: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#10B981' : '#9CA3AF' }};" viewBox="0 0 16 16" fill="none">
                @if(isset($page['children']) && count($page['children']) > 0)
                    {{-- 폴더 아이콘 (자식이 있는 경우) --}}
                    <path d="M2 3C2 2.44772 2.44772 2 3 2H6.58579C6.851 2 7.10536 2.10536 7.29289 2.29289L8.41421 3.41421C8.60174 3.60174 8.85609 3.70711 9.12132 3.70711H13C13.5523 3.70711 14 4.15482 14 4.70711V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V3Z" stroke="currentColor" stroke-width="1.5"/>
                @else
                    {{-- 페이지 아이콘 (자식이 없는 경우) --}}
                    <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                @endif
            </svg>
            @if($editingPageId == $page['id'])
                <!-- 편집 모드 -->
                <input type="text" 
                       wire:model.defer="editingTitle"
                       wire:keydown.enter="updatePageTitle"
                       wire:keydown.escape="cancelEditing"
                       wire:click.stop
                       id="edit-input-{{ $page['id'] }}"
                       style="font-size: 14px; color: #374151; font-weight: 500; background: white; border: 1px solid #D1D5DB; border-radius: 4px; padding: 2px 6px; width: 150px; outline: none; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);"
                       onclick="event.preventDefault(); event.stopPropagation();"
                       onload="this.focus(); this.select();">
                <script>
                    // 편집 입력창에 자동 포커스 및 텍스트 선택
                    setTimeout(() => {
                        const input = document.getElementById('edit-input-{{ $page['id'] }}');
                        if (input) {
                            input.focus();
                            input.select();
                        }
                    }, 100);
                </script>
            @else
                <!-- 일반 모드 -->
                <span style="font-size: 14px; color: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#10B981' : '#6B7280' }}; font-weight: {{ $currentPage && $currentPage['id'] == $page['id'] ? '500' : 'normal' }};">
                    {{ $page['title'] }}
                </span>
            @endif
        </div>
        <div style="display: flex; gap: 4px; align-items: center;">
            {{-- 드롭다운 메뉴 버튼 --}}
            <div class="page-dropdown-{{ $page['id'] }}" style="display: none; position: relative;">
                <button @click.stop="dropdownOpen = !dropdownOpen"
                        style="width: 20px; height: 20px; border: none; border-radius: 3px; background: #F3F4F6; color: #6B7280; font-size: 12px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                        onmouseover="this.style.background='#E5E7EB'"
                        onmouseout="this.style.background='#F3F4F6'"
                        title="페이지 옵션">
                    •••
                </button>
                
                {{-- 드롭다운 메뉴 --}}
                <div x-show="dropdownOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     style="position: absolute; right: 0; top: 100%; z-index: 50; margin-top: 4px; width: 160px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    
                    {{-- 이름 변경 --}}
                    <button @click.stop="
                        dropdownOpen = false;
                        const newTitle = prompt('새로운 페이지 이름을 입력하세요:', '{{ addslashes($page['title']) }}');
                        if (newTitle && newTitle.trim() !== '') {
                            $wire.updatePageTitleFromPrompt({{ $page['id'] }}, newTitle.trim());
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
                    <button @click.stop="
                        dropdownOpen = false;
                        $wire.addChildPage({{ $page['id'] }});
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
                    <button @click.stop="
                        dropdownOpen = false;
                        if (confirm('{{ $page['title'] }} 페이지를 정말 삭제하시겠습니까?\\n\\n하위 페이지가 있는 경우 먼저 하위 페이지를 삭제해야 합니다.')) {
                            $wire.deletePage({{ $page['id'] }});
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
        </div>
    </div>

    {{-- 자식 페이지들 재귀적으로 표시 --}}
    @if(isset($page['children']) && count($page['children']) > 0)
        <div style="margin-top: 4px; display: flex; flex-direction: column; gap: 4px;">
            @foreach($page['children'] as $childPage)
                @include('300-page-service.308-page-project-dashboard.302-page-tree-item', [
                    'page' => $childPage,
                    'currentPage' => $currentPage,
                    'orgId' => $orgId,
                    'projectId' => $projectId,
                    'level' => $level + 1
                ])
            @endforeach
        </div>
    @endif
</div>
