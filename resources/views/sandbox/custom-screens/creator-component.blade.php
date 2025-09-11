<div class="w-full p-6">
    <div class="text-center py-12">
        <div class="text-6xl mb-4">🚧</div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">화면 생성기</h2>
        <p class="text-gray-600 mb-4">이 기능은 현재 구현 중입니다.</p>
        
        @if($edit)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-blue-800">편집 모드: {{ $edit }}</p>
            </div>
        @endif
        
        <a href="{{ route('sandbox.custom-screens') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            ← 화면 목록으로 돌아가기
        </a>
    </div>
</div>
