<div class="file-search-component">
    {{-- 검색 입력 --}}
    <div class="p-3 border-b border-gray-200 bg-gray-100">
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="searchTerm"
                placeholder="파일명 또는 내용 검색..."
                class="w-full pl-8 pr-10 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @if($searchTerm)
                <button 
                    wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                    <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- 검색 결과 --}}
    <div class="flex-1 overflow-auto">
        @if($isSearching)
            <div class="p-4 text-center text-gray-500">
                <svg class="animate-spin h-5 w-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                검색 중...
            </div>
        @elseif(!empty($searchTerm) && empty($searchResults))
            <div class="p-4 text-center text-gray-500">
                검색 결과가 없습니다
            </div>
        @elseif(!empty($searchResults))
            <div class="divide-y divide-gray-100">
                @foreach($searchResults as $result)
                    <div class="p-3 hover:bg-gray-50 cursor-pointer group" wire:click="openSearchResult('{{ $result['fullPath'] }}')">
                        <div class="flex items-start space-x-2">
                            {{-- 아이콘 --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($result['type'] === 'folder')
                                    📁
                                @else
                                    @php
                                        $ext = $result['extension'];
                                        $icon = match($ext) {
                                            'html' => '🌐',
                                            'css' => '🎨', 
                                            'js' => '⚡',
                                            'php' => '🔥',
                                            'json' => '📊',
                                            'md' => '📝',
                                            default => '📄'
                                        };
                                    @endphp
                                    {{ $icon }}
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                {{-- 파일명 --}}
                                <div class="text-sm font-medium text-gray-900 group-hover:text-blue-600">
                                    {{ $result['name'] }}
                                </div>
                                
                                {{-- 경로 --}}
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $result['relativePath'] }}
                                </div>

                                {{-- 내용 매치인 경우 미리보기 --}}
                                @if($result['matchType'] === 'content' && !empty($result['extra']['preview']))
                                    <div class="text-xs text-gray-600 mt-1 bg-yellow-50 px-2 py-1 rounded border-l-2 border-yellow-200">
                                        <span class="font-mono">{{ $result['extra']['line'] }}:</span>
                                        {!! $result['extra']['highlighted'] !!}
                                    </div>
                                @endif
                            </div>

                            {{-- 매치 타입 표시 --}}
                            <div class="flex-shrink-0">
                                @if($result['matchType'] === 'filename')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        파일명
                                    </span>
                                @elseif($result['matchType'] === 'content')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        내용
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-4 text-center text-gray-400 text-sm">
                2글자 이상 입력하여 검색하세요
            </div>
        @endif
    </div>
</div>