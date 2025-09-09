<div>
    @if($error)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="text-red-400 text-xl mr-3">⚠️</div>
                <div>
                    <h4 class="font-medium text-red-800">렌더링 오류</h4>
                    <p class="text-red-600 text-sm mt-1">{{ $error }}</p>
                </div>
            </div>
        </div>
    @elseif($renderedContent)
        <!-- 렌더링된 콘텐츠 출력 -->
        <div class="rendered-content">
            {!! $renderedContent !!}
        </div>
        
        <!-- 디버그 정보 (개발 모드에서만) -->
        @if(config('app.debug'))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <details class="text-xs text-gray-500">
                    <summary class="cursor-pointer hover:text-gray-700">디버그 정보</summary>
                    <div class="mt-2 space-y-2">
                        <div>
                            <strong>화면 제목:</strong> {{ $screen['title'] ?? 'N/A' }}
                        </div>
                        <div>
                            <strong>화면 유형:</strong> {{ $screen['type'] ?? 'N/A' }}
                        </div>
                        @if(!empty($screen['connected_functions']))
                            @php $functions = json_decode($screen['connected_functions'], true); @endphp
                            <div>
                                <strong>연결된 함수:</strong> 
                                @foreach($functions as $func)
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs mr-1">
                                        {{ $func['name'] }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($screen['db_queries']))
                            @php $queries = json_decode($screen['db_queries'], true); @endphp
                            <div>
                                <strong>DB 쿼리:</strong> 
                                @foreach($queries as $query)
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1">
                                        {{ $query['name'] }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </details>
            </div>
        @endif
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <div class="text-gray-400 text-4xl mb-3">📱</div>
            <h4 class="font-medium text-gray-600">화면 데이터가 없습니다</h4>
            <p class="text-gray-500 text-sm mt-1">렌더링할 블레이드 템플릿을 확인해주세요.</p>
        </div>
    @endif
</div>

<style>
    /* 렌더링된 콘텐츠를 위한 기본 스타일 */
    .rendered-content {
        /* Tailwind CSS 스타일이 적용되도록 */
        @apply space-y-4;
    }
    
    .rendered-content h1 {
        @apply text-2xl font-bold text-gray-900;
    }
    
    .rendered-content h2 {
        @apply text-xl font-semibold text-gray-800;
    }
    
    .rendered-content h3 {
        @apply text-lg font-medium text-gray-700;
    }
    
    .rendered-content p {
        @apply text-gray-600;
    }
    
    .rendered-content .space-y-4 > * + * {
        @apply mt-4;
    }
    
    .rendered-content .border {
        @apply border-gray-200;
    }
    
    .rendered-content .rounded {
        @apply rounded-lg;
    }
    
    .rendered-content .p-4 {
        @apply p-4;
    }
    
    .rendered-content .bg-white {
        @apply bg-white;
    }
    
    .rendered-content .shadow {
        @apply shadow-sm;
    }
    
    .rendered-content .text-gray-500 {
        @apply text-gray-500;
    }
    
    .rendered-content .font-semibold {
        @apply font-semibold;
    }
    
    .rendered-content .text-gray-600 {
        @apply text-gray-600;
    }
</style>