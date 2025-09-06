{{-- 페이지 목록 --}}
<div style="display: flex; flex-direction: column; gap: 4px;">
    {{-- 대시보드 홈 --}}
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: white; border-radius: 6px; cursor: pointer;"
         wire:click="switchPage('dashboard-home')"
         onmouseover="this.style.background='#F9FAFB'"
         onmouseout="this.style.background='white'">
        <div style="display: flex; align-items: center; gap: 8px;">
            <svg style="width: 16px; height: 16px; color: #9CA3AF;" viewBox="0 0 16 16" fill="none">
                <path d="M2 4C2 3.44772 2.44772 3 3 3H13C13.5523 3 14 3.44772 14 4V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V4Z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M6 7H10M6 9H8" stroke="currentColor" stroke-width="1"/>
            </svg>
            <span style="font-size: 14px; font-weight: 500; color: #374151;">대시보드 홈</span>
        </div>
    </div>

    {{-- 동적 페이지 목록 --}}
    <div style="margin-left: 16px; display: flex; flex-direction: column; gap: 4px;">
        @if(count($pages) > 0)
            @foreach($pages as $page)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background: white; border-radius: 6px; cursor: pointer; {{ $currentPage && $currentPage['id'] == $page['id'] ? 'background: #F0FDF4; border-left: 2px solid #10B981;' : '' }}"
                     wire:click="switchPage({{ json_encode($page) }})"
                     onmouseover="this.style.background='#F9FAFB'"
                     onmouseout="this.style.background='{{ $currentPage && $currentPage['id'] == $page['id'] ? '#F0FDF4' : 'white' }}'">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 16px; height: 16px; color: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#10B981' : '#9CA3AF' }};" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span style="font-size: 14px; color: {{ $currentPage && $currentPage['id'] == $page['id'] ? '#10B981' : '#6B7280' }}; font-weight: {{ $currentPage && $currentPage['id'] == $page['id'] ? '500' : 'normal' }};">
                            {{ $page['title'] }}
                        </span>
                    </div>
                    <div style="display: flex; gap: 4px;">
                        <span style="font-size: 10px; padding: 2px 6px; border-radius: 3px; 
                              @if($page['status'] === 'published') 
                                  background: #ECFDF5; color: #059669;
                              @elseif($page['status'] === 'draft') 
                                  background: #FEF3C7; color: #D97706;
                              @else 
                                  background: #F3F4F6; color: #6B7280;
                              @endif">
                            @if($page['status'] === 'published')
                                공개
                            @elseif($page['status'] === 'draft')
                                임시
                            @else
                                보관
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        @else
            {{-- 빈 상태 --}}
            @if(!$isLoading)
                <div style="padding: 20px; text-align: center; color: #6B7280;">
                    <svg style="width: 32px; height: 32px; margin: 0 auto 8px; color: #D1D5DB;" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L19.7071 9.70711C19.8946 9.89464 20 10.149 20 10.4142V19C20 20.1046 19.1046 21 18 21H17Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <p style="font-size: 14px; margin: 0;">아직 페이지가 없습니다</p>
                    <p style="font-size: 12px; margin: 4px 0 0 0;">+ 버튼을 눌러 첫 페이지를 만들어보세요</p>
                </div>
            @endif
        @endif

        {{-- 로딩 상태 --}}
        @if($isLoading)
            <div style="padding: 20px; text-align: center; color: #6B7280;">
                <div style="width: 20px; height: 20px; border: 2px solid #E5E7EB; border-top: 2px solid #3B82F6; border-radius: 50%; margin: 0 auto; animation: spin 1s linear infinite;"></div>
                <p style="font-size: 14px; margin: 8px 0 0 0;">로딩 중...</p>
            </div>
        @endif
    </div>
</div>