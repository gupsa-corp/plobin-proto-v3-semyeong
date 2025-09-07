<div class="space-y-4">
    <!-- 현재 스토리지 정보 -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-blue-800 font-medium">현재 샌드박스:</span>
                <code class="bg-blue-100 px-2 py-1 rounded text-blue-900">storage-sandbox-{{ $currentStorage }}</code>
            </div>
            <button wire:click="refreshList" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                새로고침
            </button>
        </div>
    </div>

    <!-- 경로 네비게이션 -->
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <button wire:click="goToRoot" class="hover:text-blue-600 transition-colors">
                🏠 루트
            </button>
            
            @if(!empty($breadcrumbs))
                @foreach($breadcrumbs as $crumb)
                    <span>/</span>
                    <button wire:click="navigateTo('{{ $crumb['path'] }}')" 
                            class="hover:text-blue-600 transition-colors">
                        {{ $crumb['name'] }}
                    </button>
                @endforeach
            @endif
        </div>
        
        @if(!empty($currentPath))
            <div class="mt-2">
                <button wire:click="goToParent" 
                        class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                    ← 상위 디렉토리로
                </button>
            </div>
        @endif
    </div>

    <!-- 파일 목록 -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        @if(empty($items))
            <div class="p-8 text-center text-gray-500">
                <div class="text-4xl mb-2">📁</div>
                <p>이 디렉토리는 비어있거나 접근할 수 없습니다.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                이름
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                유형
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                크기
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                수정일시
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-xl mr-3">
                                            {{ $getFileIcon($item['extension'], $item['is_directory']) }}
                                        </span>
                                        @if($item['is_directory'])
                                            <button wire:click="navigateTo('{{ $item['path'] }}')" 
                                                    class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $item['name'] }}
                                            </button>
                                        @else
                                            <span class="text-gray-900">{{ $item['name'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($item['is_directory'])
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            디렉토리
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                            {{ strtoupper($item['extension'] ?? 'FILE') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['size'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['modified_at'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- 통계 정보 -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="text-sm text-gray-600">
            총 {{ count($items) }}개 항목 
            (디렉토리: {{ count(array_filter($items, fn($item) => $item['is_directory'])) }}개, 
             파일: {{ count(array_filter($items, fn($item) => !$item['is_directory'])) }}개)
        </div>
    </div>
</div>