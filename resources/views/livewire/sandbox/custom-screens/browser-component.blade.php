<div class="max-w-full mx-auto p-6">
    <!-- 헤더 -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                📱 커스텀 화면 브라우저
            </h1>
            <p class="text-gray-600 mt-1">블레이드 + 라이브와이어로 구현된 화면들을 관리하고 미리보기할 수 있습니다.</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('sandbox.custom-screen-creator') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                ✨ 새 화면 만들기
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 왼쪽: 화면 목록 -->
        <div class="space-y-4">
            <!-- 검색 및 필터 -->
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">검색</label>
                        <input wire:model.live="search" type="text" id="search"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                               placeholder="화면 제목으로 검색...">
                    </div>
                    <div>
                        <label for="filterType" class="block text-sm font-medium text-gray-700 mb-1">유형</label>
                        <select wire:model.live="filterType" id="filterType"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">전체</option>
                            <option value="dashboard">대시보드</option>
                            <option value="list">목록</option>
                            <option value="form">폼</option>
                            <option value="detail">상세</option>
                            <option value="report">리포트</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">총 {{ count($screens) }}개 화면</span>
                    <button wire:click="loadScreens" class="text-sm text-blue-600 hover:text-blue-800">
                        🔄 새로고침
                    </button>
                </div>
            </div>

            <!-- 화면 목록 -->
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($screens as $screen)
                    <div wire:click="selectScreen({{ $screen['id'] }})"
                         class="bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:shadow-md transition-shadow
                                {{ $selectedScreen && $selectedScreen['id'] == $screen['id'] ? 'border-blue-500 bg-blue-50' : '' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $screen['title'] }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $screen['description'] ?? '설명 없음' }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                        {{ ucfirst($screen['type']) }}
                                    </span>
                                    <span>{{ $screen['created_at'] }}</span>
                                    @if($screen['connected_functions'])
                                        @php $functions = json_decode($screen['connected_functions'], true); @endphp
                                        @if(count($functions) > 0)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                                🔗 {{ count($functions) }}개 함수
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col space-y-1 ml-4">
                                <button wire:click.stop="editScreen({{ $screen['id'] }})"
                                        class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1 rounded hover:bg-blue-50">
                                    ✏️ 편집
                                </button>
                                <button wire:click.stop="duplicateScreen({{ $screen['id'] }})"
                                        class="text-green-600 hover:text-green-800 text-xs px-2 py-1 rounded hover:bg-green-50">
                                    📄 복사
                                </button>
                                <button wire:click.stop="deleteScreen({{ $screen['id'] }})"
                                        class="text-red-600 hover:text-red-800 text-xs px-2 py-1 rounded hover:bg-red-50"
                                        onclick="return confirm('정말 삭제하시겠습니까?')">
                                    🗑️ 삭제
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                        <div class="text-gray-400 text-6xl mb-4">📱</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">화면이 없습니다</h3>
                        <p class="text-gray-500 mb-4">새로운 커스텀 화면을 만들어보세요!</p>
                        <a href="{{ route('sandbox.custom-screen-creator') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            ✨ 첫 번째 화면 만들기
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 오른쪽: 미리보기 -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="border-b border-gray-200 px-4 py-3">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-900">미리보기</h3>
                        @if($selectedScreen)
                            <div class="flex space-x-2">
                                <button wire:click="togglePreview" 
                                        class="text-sm px-3 py-1 rounded-md {{ $previewMode ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $previewMode ? '📝 코드 보기' : '👁️ 미리보기' }}
                                </button>
                                <button wire:click="openPreviewInNewWindow({{ $selectedScreen['id'] }})"
                                        class="text-sm px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    🚀 새 창에서 보기
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="p-4">
                    @if($selectedScreen)
                        @if($previewMode)
                            <!-- 실제 렌더링된 화면 미리보기 -->
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="mb-2 text-sm text-gray-600">렌더링 결과:</div>
                                <div class="bg-white border rounded-lg p-4 min-h-[300px]">
                                    @livewire('sandbox.custom-screens.renderer.component', ['screenData' => $selectedScreen], key('renderer-'.$selectedScreen['id']))
                                </div>
                            </div>
                        @else
                            <!-- 코드 보기 -->
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">화면 정보</h4>
                                    <div class="bg-gray-50 rounded p-3 text-sm">
                                        <div><strong>제목:</strong> {{ $selectedScreen['title'] }}</div>
                                        <div><strong>설명:</strong> {{ $selectedScreen['description'] ?? '없음' }}</div>
                                        <div><strong>유형:</strong> {{ $selectedScreen['type'] }}</div>
                                        <div><strong>생성일:</strong> {{ $selectedScreen['created_at'] }}</div>
                                    </div>
                                </div>

                                @if($selectedScreen['connected_functions'])
                                    @php $functions = json_decode($selectedScreen['connected_functions'], true); @endphp
                                    @if(count($functions) > 0)
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">연결된 함수</h4>
                                            <div class="space-y-2">
                                                @foreach($functions as $func)
                                                    <div class="bg-green-50 border border-green-200 rounded p-2 text-sm">
                                                        <div class="font-medium text-green-800">{{ $func['name'] }}</div>
                                                        <div class="text-green-600">{{ $func['description'] ?? '' }}</div>
                                                        @if(!empty($func['binding']))
                                                            <div class="text-xs text-green-500 mt-1">바인딩: {{ $func['binding'] }}</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">블레이드 템플릿</h4>
                                    <pre class="bg-gray-900 text-green-400 p-3 rounded text-xs overflow-x-auto"><code>{{ $selectedScreen['blade_template'] ?? '코드가 없습니다.' }}</code></pre>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">👈</div>
                            <p>화면을 선택하여 미리보기를 확인하세요.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 플래시 메시지 -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openPreviewWindow', (event) => {
            const { url } = event;
            const width = Math.min(1200, screen.width * 0.8);
            const height = Math.min(800, screen.height * 0.8);
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            
            window.open(url, 'preview-window', 
                `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`);
        });
    });
</script>