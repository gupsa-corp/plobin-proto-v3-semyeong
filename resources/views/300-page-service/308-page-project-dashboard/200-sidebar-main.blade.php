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
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: {{ $currentPageId == $page->id ? '#EFF6FF' : 'transparent' }}; border-radius: 6px;"
                         onmouseover="if({{ $currentPageId == $page->id ? 'false' : 'true' }}) this.style.background='#F9FAFB'"
                         onmouseout="if({{ $currentPageId == $page->id ? 'false' : 'true' }}) this.style.background='{{ $currentPageId == $page->id ? '#EFF6FF' : 'transparent' }}'">
                        <a href="{{ route('project.dashboard.page', ['id' => request()->route('id'), 'projectId' => $projectId, 'pageId' => $page->id]) }}" 
                           style="display: flex; align-items: center; gap: 8px; color: {{ $currentPageId == $page->id ? '#2563EB' : '#6B7280' }}; text-decoration: none; cursor: pointer; flex: 1;">
                            <span style="font-size: 14px; font-weight: {{ $currentPageId == $page->id ? '600' : '400' }};">{{ $page->title }}</span>
                        </a>
                        
                        {{-- 드롭다운 버튼 --}}
                        <div class="page-dropdown-container" style="position: relative;">
                            <button onclick="toggleDropdown({{ $page->id }})"
                                    style="width: 24px; height: 24px; border: 1px solid #D1D5DB; border-radius: 4px; background: #F8F9FA; color: #495057; font-size: 14px; font-weight: bold; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                                    onmouseover="this.style.background='#E9ECEF'; this.style.borderColor='#ADB5BD';"
                                    onmouseout="this.style.background='#F8F9FA'; this.style.borderColor='#D1D5DB';"
                                    title="페이지 옵션 (ID: {{ $page->id }})">
                                ⋮
                            </button>
                            
                            <div id="dropdown-{{ $page->id }}" class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; z-index: 9999; margin-top: 4px; width: 160px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
                                
                                {{-- 이름 변경 --}}
                                <button onclick="
                                    const newTitle = prompt('새로운 페이지 이름을 입력하세요:', '{{ addslashes($page->title) }}');
                                    if (newTitle && newTitle.trim() !== '') {
                                        updatePageTitle({{ $page->id }}, newTitle.trim());
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
                                    addChildPage({{ $page->id }});
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
                                    if (confirm('{{ addslashes($page->title) }} 페이지를 정말 삭제하시겠습니까?\\n\\n하위 페이지가 있는 경우 먼저 하위 페이지를 삭제해야 합니다.')) {
                                        deletePage({{ $page->id }});
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
                    </div>
                    
                    {{-- 하위 페이지 표시 --}}
                    @php
                        $childPages = \App\Models\ProjectPage::where('parent_id', $page->id)
                            ->orderBy('sort_order')
                            ->get();
                    @endphp
                    
                    @if($childPages->count() > 0)
                        <div style="margin-left: 16px; margin-top: 4px;">
                            @foreach($childPages as $childPage)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px 8px; background: {{ $currentPageId == $childPage->id ? '#EFF6FF' : 'transparent' }}; border-radius: 4px; margin-bottom: 2px;"
                                     onmouseover="if({{ $currentPageId == $childPage->id ? 'false' : 'true' }}) this.style.background='#F9FAFB'"
                                     onmouseout="if({{ $currentPageId == $childPage->id ? 'false' : 'true' }}) this.style.background='{{ $currentPageId == $childPage->id ? '#EFF6FF' : 'transparent' }}'">
                                    <a href="{{ route('project.dashboard.page', ['id' => request()->route('id'), 'projectId' => $projectId, 'pageId' => $childPage->id]) }}" 
                                       style="display: flex; align-items: center; gap: 8px; color: {{ $currentPageId == $childPage->id ? '#2563EB' : '#9CA3AF' }}; text-decoration: none; cursor: pointer; flex: 1;">
                                        <span style="font-size: 13px; font-weight: {{ $currentPageId == $childPage->id ? '600' : '400' }};">{{ $childPage->title }}</span>
                                    </a>
                                    
                                    {{-- 하위 페이지 드롭다운 버튼 --}}
                                    <div class="page-dropdown-container" style="position: relative;">
                                        <button onclick="toggleDropdown({{ $childPage->id }})"
                                                style="width: 20px; height: 20px; border: 1px solid #D1D5DB; border-radius: 3px; background: #F8F9FA; color: #495057; font-size: 12px; font-weight: bold; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                                                onmouseover="this.style.background='#E9ECEF'; this.style.borderColor='#ADB5BD';"
                                                onmouseout="this.style.background='#F8F9FA'; this.style.borderColor='#D1D5DB';"
                                                title="페이지 옵션 (ID: {{ $childPage->id }})">
                                            ⋮
                                        </button>
                                        
                                        <div id="dropdown-{{ $childPage->id }}" class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; z-index: 9999; margin-top: 4px; width: 160px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;">
                                            {{-- 이름 변경 --}}
                                            <button onclick="
                                                const newTitle = prompt('새로운 페이지 이름을 입력하세요:', '{{ addslashes($childPage->title) }}');
                                                if (newTitle && newTitle.trim() !== '') {
                                                    updatePageTitle({{ $childPage->id }}, newTitle.trim());
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
                                            
                                            {{-- 하위 페이지 추가 (depth 2 제한으로 비활성화) --}}
                                            {{-- 하위 페이지에서는 더 이상 하위 페이지를 생성할 수 없음 --}}
                                            
                                            {{-- 구분선 --}}
                                            <div style="height: 1px; background: #E5E7EB; margin: 4px 0;"></div>
                                            
                                            {{-- 삭제 --}}
                                            <button onclick="
                                                if (confirm('{{ addslashes($childPage->title) }} 페이지를 정말 삭제하시겠습니까?')) {
                                                    deletePage({{ $childPage->id }});
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
                                </div>
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

// 드롭다운 관리
let currentOpenDropdownId = null;

function toggleDropdown(pageId) {
    closeAllDropdowns();
    
    if (currentOpenDropdownId !== pageId) {
        const dropdown = document.getElementById('dropdown-' + pageId);
        if (dropdown) {
            dropdown.style.display = 'block';
            currentOpenDropdownId = pageId;
        }
    } else {
        currentOpenDropdownId = null;
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
        dropdown.style.display = 'none';
    });
    currentOpenDropdownId = null;
}

// 문서 클릭 시 드롭다운 닫기
document.addEventListener('click', function(e) {
    if (!e.target.closest('.page-dropdown-container')) {
        closeAllDropdowns();
    }
});

// 페이지 제목 업데이트
async function updatePageTitle(pageId, newTitle) {
    try {
        const pathParts = window.location.pathname.split('/');
        const orgId = pathParts[2];
        const projectId = pathParts[4];
        
        const response = await fetch(`/organizations/${orgId}/projects/${projectId}/pages/${pageId}/title`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: newTitle
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // 페이지 새로고침으로 변경사항 반영
            window.location.reload();
        } else {
            alert(result.error || '페이지 이름 변경에 실패했습니다.');
        }
    } catch (error) {
        console.error('Title update error:', error);
        alert('페이지 이름 변경 중 오류가 발생했습니다.');
    }
}

// 하위 페이지 추가
async function addChildPage(parentId) {
    try {
        const pathParts = window.location.pathname.split('/');
        const orgId = pathParts[2];
        const projectId = pathParts[4];
        
        const response = await fetch(`/organizations/${orgId}/projects/${projectId}/pages/create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                parent_id: parentId,
                title: '새 하위 페이지'
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // 페이지 새로고침으로 변경사항 반영
            window.location.reload();
        } else {
            alert(result.error || '하위 페이지 생성에 실패했습니다.');
        }
    } catch (error) {
        console.error('Child page creation error:', error);
        alert('하위 페이지 생성 중 오류가 발생했습니다.');
    }
}

// 페이지 삭제
async function deletePage(pageId) {
    try {
        const pathParts = window.location.pathname.split('/');
        const orgId = pathParts[2];
        const projectId = pathParts[4];
        
        const response = await fetch(`/organizations/${orgId}/projects/${projectId}/pages/${pageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // 현재 페이지가 삭제된 페이지라면 프로젝트 대시보드로 이동
            const currentPageId = pathParts[6];
            if (currentPageId == pageId) {
                window.location.href = `/organizations/${orgId}/projects/${projectId}`;
            } else {
                // 페이지 새로고침으로 변경사항 반영
                window.location.reload();
            }
        } else {
            alert(result.error || '페이지 삭제에 실패했습니다.');
        }
    } catch (error) {
        console.error('Page deletion error:', error);
        alert('페이지 삭제 중 오류가 발생했습니다.');
    }
}
</script>
