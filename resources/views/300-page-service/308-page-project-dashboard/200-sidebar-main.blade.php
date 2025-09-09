{{-- 프로젝트 대시보드 사이드바 --}}
<nav class="sidebar" style="position: fixed; left: 0; top: 0; width: 240px; height: 100vh; background: #ffffff; border-right: 1px solid #E1E1E4; display: flex; flex-direction: column; z-index: 10; box-sizing: border-box;">
    @include('000-common-assets.100-logo')

    {{-- 프로젝트 정보 섹션 --}}
    <div style="padding: 20px; border-bottom: 1px solid #E1E1E4;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
            <svg style="width: 16px; height: 16px; color: #6B7280;" viewBox="0 0 16 16" fill="none">
                <path d="M2 3C2 2.44772 2.44772 2 3 2H6.58579C6.851 2 7.10536 2.10536 7.29289 2.29289L8.41421 3.41421C8.60174 3.60174 8.85609 3.70711 9.12132 3.70711H13C13.5523 3.70711 14 4.15482 14 4.70711V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V3Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">프로젝트</h2>
        </div>
    </div>

    {{-- 페이지 네비게이션 --}}
    <div style="flex: 1; overflow-y: auto; padding: 20px;">
        {{-- 섹션 헤더 --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <span style="font-size: 12px; font-weight: 500; color: #6B7280;">프로젝트 페이지</span>
            <button 
                id="create-page-btn"
                onclick="createNewPage()"
                style="width: 24px; height: 24px; border: none; border-radius: 4px; background: #F3F4F6; color: #6B7280; font-size: 14px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                onmouseover="this.style.background='#E5E7EB'"
                onmouseout="this.style.background='#F3F4F6'"
                title="새 페이지 추가">
                +
            </button>
        </div>

        {{-- 정적 페이지 목록 --}}
        @php
            $projectId = request()->route('projectId') ?? 1;
            $currentPageId = request()->route('pageId') ?? null;
            $pages = \App\Models\ProjectPage::where('project_id', $projectId)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();
        @endphp

        @if($pages->count() > 0)
            @foreach($pages as $page)
                <div style="margin-bottom: 8px;">
                    <a href="{{ route('project.dashboard.page', ['id' => request()->route('id'), 'projectId' => $projectId, 'pageId' => $page->id]) }}" 
                       style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px; color: {{ $currentPageId == $page->id ? '#2563EB' : '#6B7280' }}; text-decoration: none; border-radius: 6px; cursor: pointer; background: {{ $currentPageId == $page->id ? '#EFF6FF' : 'transparent' }};"
                       onmouseover="if({{ $currentPageId == $page->id ? 'false' : 'true' }}) this.style.background='#F9FAFB'"
                       onmouseout="if({{ $currentPageId == $page->id ? 'false' : 'true' }}) this.style.background='transparent'">
                        <span style="font-size: 14px; font-weight: {{ $currentPageId == $page->id ? '600' : '400' }};">{{ $page->title }}</span>
                    </a>
                    
                    {{-- 하위 페이지 표시 --}}
                    @php
                        $childPages = \App\Models\ProjectPage::where('parent_id', $page->id)
                            ->orderBy('sort_order')
                            ->get();
                    @endphp
                    
                    @if($childPages->count() > 0)
                        <div style="margin-left: 16px; margin-top: 4px;">
                            @foreach($childPages as $childPage)
                                <a href="{{ route('project.dashboard.page', ['id' => request()->route('id'), 'projectId' => $projectId, 'pageId' => $childPage->id]) }}" 
                                   style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 6px 8px; color: {{ $currentPageId == $childPage->id ? '#2563EB' : '#9CA3AF' }}; text-decoration: none; border-radius: 4px; cursor: pointer; background: {{ $currentPageId == $childPage->id ? '#EFF6FF' : 'transparent' }}; margin-bottom: 2px;"
                                   onmouseover="if({{ $currentPageId == $childPage->id ? 'false' : 'true' }}) this.style.background='#F9FAFB'"
                                   onmouseout="if({{ $currentPageId == $childPage->id ? 'false' : 'true' }}) this.style.background='transparent'">
                                    <span style="font-size: 13px; font-weight: {{ $currentPageId == $childPage->id ? '600' : '400' }};">{{ $childPage->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div style="text-align: center; color: #9CA3AF; font-size: 14px; padding: 20px;">
                페이지가 없습니다.<br>
                <span style="font-size: 12px;">+ 버튼을 클릭하여 첫 페이지를 만들어보세요.</span>
            </div>
        @endif
    </div>

    {{-- 빠른 액션 섹션 --}}
    <div style="padding: 20px; border-top: 1px solid #E5E7EB;">
        <span style="font-size: 12px; font-weight: 500; color: #6B7280; display: block; margin-bottom: 12px;">프로젝트 관리</span>

        <a href="{{ route('project.dashboard.project.settings.name', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}"
           style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px; color: #6B7280; text-decoration: none; border-radius: 6px; cursor: pointer; margin-bottom: 4px;"
           onmouseover="this.style.background='white'"
           onmouseout="this.style.background='none'">
            <span style="font-size: 14px;">프로젝트 설정</span>
        </a>

        @if(request()->route('pageId'))
        <a href="{{ route('project.dashboard.page.settings', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
           style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px; color: #6B7280; text-decoration: none; border-radius: 6px; cursor: pointer;"
           onmouseover="this.style.background='white'"
           onmouseout="this.style.background='none'">
            <span style="font-size: 14px;">페이지 설정</span>
        </a>
        @endif
    </div>
</nav>

<script>
async function createNewPage() {
    const button = document.getElementById('create-page-btn');
    const originalText = button.innerHTML;
    
    // 로딩 상태 표시
    button.innerHTML = '⏳';
    button.disabled = true;
    button.style.cursor = 'not-allowed';
    
    try {
        // 현재 URL에서 조직 ID와 프로젝트 ID 추출
        const pathParts = window.location.pathname.split('/');
        const orgId = pathParts[2]; // /organizations/{id}/projects/{projectId}
        const projectId = pathParts[4];
        
        const response = await fetch(`/organizations/${orgId}/projects/${projectId}/pages/create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success && result.redirect_url) {
            // 성공적으로 생성된 경우 페이지로 이동
            window.location.href = result.redirect_url;
        } else {
            // 오류 처리
            alert(result.error || '페이지 생성 중 오류가 발생했습니다.');
            button.innerHTML = originalText;
            button.disabled = false;
            button.style.cursor = 'pointer';
        }
    } catch (error) {
        console.error('Page creation error:', error);
        alert('페이지 생성 중 오류가 발생했습니다.');
        button.innerHTML = originalText;
        button.disabled = false;
        button.style.cursor = 'pointer';
    }
}
</script>
