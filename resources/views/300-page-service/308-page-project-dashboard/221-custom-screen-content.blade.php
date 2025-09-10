<!-- 커스텀 화면 콘텐츠 컴포넌트 -->
<!-- 커스텀 화면 헤더 -->
<div class="mb-6 border-b border-gray-200 pb-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $customScreen['title'] ?? '커스텀 화면' }}</h1>
            <p class="text-gray-600 mt-1">{{ $customScreen['description'] ?? '' }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ ucfirst($customScreen['type'] ?? 'custom') }}
            </span>
            <span class="text-xs text-gray-500">{{ $customScreen['created_at'] ?? '' }}</span>
        </div>
    </div>
</div>

<!-- 커스텀 화면 컨텐츠 렌더링 -->
<div class="custom-screen-content">
    @if(!empty($customScreen['content']))
        {!! $customScreen['content'] !!}
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">커스텀 화면 콘텐츠를 렌더링할 수 없습니다.</p>
        </div>
    @endif
</div>
