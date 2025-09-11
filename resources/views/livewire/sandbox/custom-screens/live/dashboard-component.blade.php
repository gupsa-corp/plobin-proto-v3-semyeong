<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">실시간 대시보드</h2>
            <p class="text-gray-600">샌드박스 데이터베이스와 연동된 실시간 대시보드입니다.</p>
        </div>
        <button wire:click="refreshData" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            🔄 새로고침
        </button>
    </div>
    
    <!-- 통계 카드 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">전체 조직</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_organizations'] }}개</p>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-lg font-semibold text-green-800 mb-2">전체 프로젝트</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_projects'] }}개</p>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h3 class="text-lg font-semibold text-purple-800 mb-2">전체 사용자</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_users'] }}명</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 최근 활동 -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">최근 활동</h3>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                        <div>
                            <p class="font-medium text-gray-800">{{ $activity['action'] }}</p>
                            <p class="text-sm text-gray-500">{{ $activity['user'] }} - {{ $activity['project'] ?? '' }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        최근 활동이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- 시스템 상태 -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">시스템 상태</h3>
            <div class="space-y-3">
                @foreach($systemStatus as $status)
                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-{{ $status['color'] }}-500 rounded-full mr-3"></div>
                            <span class="font-medium text-gray-800">{{ $status['name'] }}</span>
                        </div>
                        <span class="text-sm text-{{ $status['color'] }}-600">
                            {{ $status['status'] === 'normal' ? '정상' : '주의' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Livewire 실시간 영역 -->
    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">실시간 Livewire 연동 영역</h3>
        <p class="text-blue-600 mb-4">샌드박스 데이터베이스와 실시간으로 연동됩니다.</p>
        
        <div class="mt-4 flex space-x-2">
            <button wire:click="refreshData" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                데이터 새로고침
            </button>
            <button class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                설정 변경
            </button>
        </div>
    </div>

    <!-- 로딩 상태 표시 -->
    <div wire:loading class="fixed top-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded shadow-lg z-50">
        🔄 데이터를 새로고침하는 중...
    </div>
</div>