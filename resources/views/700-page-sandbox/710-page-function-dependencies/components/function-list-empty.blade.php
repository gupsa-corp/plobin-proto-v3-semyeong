<div class="text-center py-8 text-gray-500">
    <div class="text-4xl mb-2">📭</div>
    <h4 class="text-lg font-medium mb-1">함수가 없습니다</h4>
    <p class="text-sm">
        @if($searchTerm || $filterCategory)
            검색 조건을 변경해보세요.
        @else
            등록된 함수가 없습니다.
        @endif
    </p>
    
    @if($searchTerm || $filterCategory)
        <button 
            wire:click="$set('searchTerm', ''); $set('filterCategory', '')" 
            class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium"
        >
            모든 함수 보기
        </button>
    @endif
</div>