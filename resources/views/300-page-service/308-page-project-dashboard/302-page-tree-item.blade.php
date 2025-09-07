{{-- 재귀적 페이지 트리 아이템 --}}
<div style="margin-left: {{ $level * 16 }}px;">
    <a href="{{ route('project.dashboard.page', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $page['id']]) }}"
       style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}; border-radius: 6px; cursor: pointer; text-decoration: none; {{ $currentPage && $currentPage['id'] == $page['id'] ? 'border-left: 2px solid #10B981;' : '' }}"
         onmouseover="this.style.background='#F9FAFB'; const btn = document.querySelector('.add-child-btn-{{ $page['id'] }}'); if(btn) btn.style.display='flex';"
         onmouseout="this.style.background='{{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}'; const btn = document.querySelector('.add-child-btn-{{ $page['id'] }}'); if(btn) btn.style.display='none';">
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
            <span style="font-size: 14px; color: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#10B981' : '#6B7280' }}; font-weight: {{ $currentPage && $currentPage['id'] == $page['id'] ? '500' : 'normal' }};">
                {{ $page['title'] }}
            </span>
        </div>
        <div style="display: flex; gap: 4px; align-items: center;">
            {{-- 하위 페이지 추가 버튼 --}}
            <button wire:click="addChildPage({{ $page['id'] }})"
                    style="display: none; width: 20px; height: 20px; border: none; border-radius: 3px; background: #F3F4F6; color: #6B7280; font-size: 12px; line-height: 1; cursor: pointer; align-items: center; justify-content: center;"
                    onmouseover="this.style.background='#E5E7EB'"
                    onmouseout="this.style.background='#F3F4F6'"
                    title="하위 페이지 추가"
                    class="add-child-btn-{{ $page['id'] }}">
                +
            </button>
        </div>
    </a>

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
