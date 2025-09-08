<div class="h-full p-6 overflow-auto">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">함수 템플릿 라이브러리</h3>
            <p class="text-gray-600">사용 가능한 함수 템플릿을 둘러보고 새 함수 생성에 활용하세요.</p>
        </div>

        @if($templateService)
            @php
                try {
                    $templates = $templateService->getTemplates();
                    $categories = $templateService->getCategories();
                } catch (\Exception $e) {
                    $templates = [];
                    $categories = [];
                }
            @endphp
            
            @if(!empty($templates))
                {{-- Templates Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($templates as $templateId => $template)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <div class="text-2xl">{{ $template['icon'] ?? '📦' }}</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $template['name'] }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $template['description'] }}</p>
                                    
                                    {{-- Category Badge --}}
                                    @if(isset($template['category']) && isset($categories[$template['category']]))
                                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mb-3">
                                            {{ $categories[$template['category']]['name'] }}
                                        </span>
                                    @endif

                                    {{-- Tags --}}
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach($template['tags'] ?? [] as $tag)
                                            <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">{{ $tag }}</span>
                                        @endforeach
                                    </div>

                                    {{-- Parameters --}}
                                    @if(!empty($template['parameters']))
                                        <div class="text-xs text-gray-500 mb-3">
                                            <strong>파라미터:</strong>
                                            @foreach($template['parameters'] as $param)
                                                <span class="inline-block ml-1">{{ $param['name'] }}</span>{{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Actions --}}
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="switchTab('creator')"
                                            class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                                        >
                                            사용하기
                                        </button>
                                        <button class="px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                            미리보기
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📝</div>
                    <p class="text-gray-600">사용 가능한 템플릿이 없습니다.</p>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">⚙️</div>
                <p class="text-gray-600">템플릿 서비스를 로드하는 중입니다...</p>
            </div>
        @endif
    </div>
</div>