{{-- 재귀적 페이지 트리 아이템 --}}
<div style="margin-left: {{ $level * 16 }}px;" 
     class="page-item" 
     data-page-id="{{ $page['id'] }}"
     data-parent-id="{{ $page['parent_id'] ?? '' }}">
    
    <div @if($editingPageId != $page['id']) @click="if(!$event.target.closest('.page-dropdown-container')) { window.location.href='{{ route('project.dashboard.page', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $page['id']]) }}' }" @endif
         style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}; border-radius: 6px; text-decoration: none; {{ $currentPage && $currentPage['id'] == $page['id'] ? 'border-left: 2px solid #10B981;' : '' }} border: 1px solid transparent; transition: all 0.2s ease;"
         onmouseover="if({{ $editingPageId == $page['id'] ? 'false' : 'true' }}) { this.style.background='#F9FAFB'; }"
         onmouseout="if({{ $editingPageId == $page['id'] ? 'false' : 'true' }}) { this.style.background='{{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}'; }"
         class="sortable-item">
        <div style="display: flex; align-items: center; gap: 8px; cursor: {{ $editingPageId == $page['id'] ? 'default' : 'grab' }}; flex: 1;"
             class="drag-handle">
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
            {{-- Livewire 페이지 메뉴 드롭다운 --}}
            <livewire:page-menu-dropdown :page="$page" :key="'page-menu-'.$page['id']" />
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
