{{-- 최근 활동 로그 --}}
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">최근 활동</h3>
        <button wire:click="refreshActivities" class="text-sm text-blue-600 hover:text-blue-500">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            새로고침
        </button>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @foreach($activities as $activity)
            <div class="flex items-start">
                <div class="flex-shrink-0 w-2 h-2 {{ $activity['icon_color'] }} rounded-full mt-2"></div>
                <div class="ml-3">
                    <div class="text-sm text-gray-900">{{ $activity['title'] }}</div>
                    <div class="text-xs text-gray-500">{{ $activity['description'] }} - {{ $activity['time'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="inline-flex items-center text-sm text-gray-500">
                <span>모든 활동 보기</span>
                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">개발 필요</span>
            </div>
        </div>
    </div>
</div>