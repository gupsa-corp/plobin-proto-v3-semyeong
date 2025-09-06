{{-- 프로젝트 대시보드 사이드바 --}}
<nav class="sidebar" style="position: fixed; left: 0; top: 0; width: 240px; height: 100vh; background: #ffffff; border-right: 1px solid #E1E1E4; display: flex; flex-direction: column; z-index: 10; box-sizing: border-box;">
    @include('000-common-assets.100-logo')

    {{-- 프로젝트 정보 섹션 --}}
    <div style="padding: 20px; border-bottom: 1px solid #E1E1E4;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
            <svg style="width: 16px; height: 16px; color: #6B7280;" viewBox="0 0 16 16" fill="none">
                <path d="M2 3C2 2.44772 2.44772 2 3 2H6.58579C6.851 2 7.10536 2.10536 7.29289 2.29289L8.41421 3.41421C8.60174 3.60174 8.85609 3.70711 9.12132 3.70711H13C13.5523 3.70711 14 4.15482 14 4.70711V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V3Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">프로젝트명</h2>
        </div>
        <p style="font-size: 12px; color: #6B7280; margin: 0;">프로젝트 대시보드</p>
    </div>

    {{-- 페이지 네비게이션 --}}
    <div style="flex: 1; overflow-y: auto; padding: 20px;">
        {{-- 섹션 헤더 --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <span style="font-size: 12px; font-weight: 500; color: #6B7280;">프로젝트 페이지</span>
            <button style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; color: #9CA3AF; border: none; background: none; cursor: pointer;">
                <svg style="width: 16px; height: 16px;" viewBox="0 0 16 16" fill="none">
                    <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        {{-- 페이지 목록 --}}
        <div style="display: flex; flex-direction: column; gap: 4px;">
            {{-- 대시보드 홈 --}}
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: white; border-radius: 6px; cursor: pointer;"
                 @click="switchPage('dashboard-home')"
                 onmouseover="this.style.background='#F9FAFB'"
                 onmouseout="this.style.background='white'">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 16px; height: 16px; color: #9CA3AF;" viewBox="0 0 16 16" fill="none">
                        <path d="M2 4C2 3.44772 2.44772 3 3 3H13C13.5523 3 14 3.44772 14 4V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V4Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6 7H10M6 9H8" stroke="currentColor" stroke-width="1"/>
                    </svg>
                    <span style="font-size: 14px; font-weight: 500; color: #374151;">대시보드 홈</span>
                </div>
                <button style="width: 16px; height: 16px; color: #9CA3AF; border: none; background: none; cursor: pointer;" @click.stop>
                    <svg viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            {{-- 하위 페이지들 --}}
            <div style="margin-left: 16px; display: flex; flex-direction: column; gap: 4px;">
                {{-- 활성 페이지 --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: #ECFDF5; border-left: 2px solid #10B981; border-radius: 6px; cursor: pointer;"
                     @click="switchPage('page-1')">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 16px; height: 16px; color: #059669;" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span style="font-size: 14px; font-weight: 500; color: #059669;">페이지 1</span>
                    </div>
                    <button style="width: 16px; height: 16px; color: #34D399; border: none; background: none; cursor: pointer;" @click.stop>
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                {{-- 일반 페이지들 --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: white; border-radius: 6px; cursor: pointer;"
                     @click="switchPage('page-2')"
                     onmouseover="this.style.background='#F9FAFB'"
                     onmouseout="this.style.background='white'">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 16px; height: 16px; color: #9CA3AF;" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span style="font-size: 14px; color: #6B7280;">페이지 2</span>
                    </div>
                    <button style="width: 16px; height: 16px; color: #9CA3AF; border: none; background: none; cursor: pointer;" @click.stop>
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: white; border-radius: 6px; cursor: pointer;"
                     @click="switchPage('page-3')"
                     onmouseover="this.style.background='#F9FAFB'"
                     onmouseout="this.style.background='white'">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 16px; height: 16px; color: #9CA3AF;" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span style="font-size: 14px; color: #6B7280;">페이지 3</span>
                    </div>
                    <button style="width: 16px; height: 16px; color: #9CA3AF; border: none; background: none; cursor: pointer;" @click.stop>
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                {{-- 하위 페이지 추가 버튼 --}}
                <button style="display: flex; align-items: center; gap: 8px; padding: 8px; color: #9CA3AF; border: none; background: none; border-radius: 6px; cursor: pointer;"
                        onmouseover="this.style.color='#10B981'; this.style.background='#ECFDF5'"
                        onmouseout="this.style.color='#9CA3AF'; this.style.background='none'">
                    <svg style="width: 16px; height: 16px;" viewBox="0 0 16 16" fill="none">
                        <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span style="font-size: 14px;">하위 페이지 추가</span>
                </button>
            </div>
        </div>
    </div>

    {{-- 빠른 액션 섹션 (Livewire 컴포넌트) --}}
    <livewire:service.project-dashboard.quick-actions-livewire />
</nav>
