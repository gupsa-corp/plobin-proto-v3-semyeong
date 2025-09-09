<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">샘플 대시보드</h2>
    <p class="text-gray-600 mb-6">파일 기반 커스텀 화면 테스트용 대시보드입니다.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">전체 조직</h3>
            <p class="text-3xl font-bold text-blue-600">3개</p>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h3 class="text-lg font-semibold text-green-800 mb-2">전체 프로젝트</h3>
            <p class="text-3xl font-bold text-green-600">12개</p>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h3 class="text-lg font-semibold text-purple-800 mb-2">전체 사용자</h3>
            <p class="text-3xl font-bold text-purple-600">25명</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">최근 활동</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-white rounded border">
                    <div>
                        <p class="font-medium text-gray-800">새 프로젝트 생성</p>
                        <p class="text-sm text-gray-500">홍길동</p>
                    </div>
                    <span class="text-xs text-gray-400">2시간 전</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white rounded border">
                    <div>
                        <p class="font-medium text-gray-800">커스텀 화면 업데이트</p>
                        <p class="text-sm text-gray-500">김철수</p>
                    </div>
                    <span class="text-xs text-gray-400">5시간 전</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white rounded border">
                    <div>
                        <p class="font-medium text-gray-800">새 사용자 가입</p>
                        <p class="text-sm text-gray-500">이영희</p>
                    </div>
                    <span class="text-xs text-gray-400">1일 전</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">시스템 상태</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-white rounded border">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="font-medium text-gray-800">서버 상태</span>
                    </div>
                    <span class="text-sm text-green-600">정상</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white rounded border">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="font-medium text-gray-800">데이터베이스</span>
                    </div>
                    <span class="text-sm text-yellow-600">주의</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white rounded border">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="font-medium text-gray-800">API 서비스</span>
                    </div>
                    <span class="text-sm text-green-600">정상</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Livewire Component 예시 -->
    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Livewire 연동 영역</h3>
        <p class="text-blue-600">여기서 Livewire 컴포넌트를 사용할 수 있습니다.</p>
        
        <div class="mt-4 flex space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                액션 버튼 1
            </button>
            <button class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                액션 버튼 2
            </button>
        </div>
    </div>
</div>