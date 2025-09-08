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
                x-data
                @click="$dispatch('add-parent-page')"
                style="width: 24px; height: 24px; border: none; border-radius: 4px; background: #F3F4F6; color: #6B7280; font-size: 14px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;"
                onmouseover="this.style.background='#E5E7EB'"
                onmouseout="this.style.background='#F3F4F6'"
                title="새 페이지 추가">
                +
            </button>
        </div>

        @livewire('service.project-dashboard.page-list-livewire', [
            'orgId' => request()->route('id'),
            'projectId' => request()->route('projectId') ?? 1,
            'currentPageId' => request()->route('pageId') ?? ($currentPageId ?? null)
        ])
    </div>

    {{-- 빠른 액션 섹션 (Livewire 컴포넌트) --}}
    <livewire:service.project-dashboard.quick-actions-livewire />
</nav>
