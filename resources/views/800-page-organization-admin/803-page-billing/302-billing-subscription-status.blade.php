{{-- 현재 플랜 정보 --}}
<div id="subscription-card" class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white mb-6 hidden">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h3 id="plan-name" class="text-xl font-semibold"></h3>
                <span id="plan-status" class="px-2.5 py-0.5 bg-blue-500 rounded-full text-xs font-medium"></span>
            </div>
            <p class="text-blue-100 mb-4">팀 협업을 위한 플랜</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-blue-100 text-sm">다음 결제일</p>
                    <p id="next-billing-date" class="text-lg font-medium"></p>
                </div>
                <div>
                    <p class="text-blue-100 text-sm">사용 중인 멤버</p>
                    <p id="current-members" class="text-lg font-medium"></p>
                </div>
            </div>
            <div class="mt-4">
                <a href="/organizations/{{ request()->route('organizationId') ?? request()->route('organization')->id ?? 1 }}/admin/billing/plan-calculator" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    요금제 변경
                </a>
            </div>
        </div>
        <div class="hidden md:block">
            <div class="w-24 h-24 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- 구독 없음 상태 --}}
<div id="no-subscription-card" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-6 hidden">
    <div class="w-16 h-16 mx-auto bg-gray-200 rounded-lg flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">활성 구독이 없습니다</h3>
    <p class="text-gray-600 mb-4">플랜을 선택하여 서비스를 시작하세요</p>
    <a href="/organizations/{{ request()->route('organizationId') ?? request()->route('organization')->id ?? 1 }}/admin/billing/plan-calculator" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
        </svg>
        요금제 선택
    </a>
</div>