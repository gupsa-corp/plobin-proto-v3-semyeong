<div class="space-y-6">
    <!-- 성공 메시지 -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- 헤더 -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Blade 템플릿 목록</h2>
            <p class="text-gray-600 mt-1">생성된 Blade 템플릿들을 관리하고 미리보기하세요</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="loadBlades"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>새로고침</span>
            </button>
        </div>
    </div>

    <!-- 검색 및 필터 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <input type="text"
                       wire:model.live="search"
                       placeholder="Blade 템플릿 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <select wire:model.live="filterType"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">모든 타입</option>
                <option value="basic">기본</option>
                <option value="component">컴포넌트</option>
                <option value="layout">레이아웃</option>
                <option value="form">폼</option>
            </select>
        </div>
    </div>

    <!-- Blade 목록 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($blades as $blade)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $blade['title'] ?? '제목 없음' }}</h3>
                            <p class="text-sm text-gray-600">{{ $blade['description'] ?? '설명 없음' }}</p>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                            {{ ucfirst($blade['type'] ?? 'basic') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <span>생성일: {{ isset($blade['created_at']) ? date('Y-m-d', strtotime($blade['created_at'])) : '알 수 없음' }}</span>
                        <span>크기: {{ isset($blade['size']) ? number_format($blade['size']) : '0' }} bytes</span>
                    </div>

                    <div class="flex gap-2">
                        <button wire:click="previewBlade({{ $blade['id'] }})"
                                class="flex-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>미리보기</span>
                        </button>
                        <button wire:click="editBlade({{ $blade['id'] }})"
                                class="flex-1 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>편집</span>
                        </button>
                        <button wire:click="deleteBlade({{ $blade['id'] }})"
                                onclick="return confirm('정말로 이 Blade 템플릿을 삭제하시겠습니까?')"
                                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Blade 템플릿이 없습니다</h3>
                    <p class="text-sm text-gray-600 mb-4">아직 생성된 Blade 템플릿이 없습니다.</p>
                    <button wire:click="loadBlades"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        새로고침
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- 통계 정보 -->
    @if(!empty($blades))
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">통계</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ count($blades) }}</div>
                    <div class="text-sm text-gray-600">총 템플릿</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">
                        @php
                            $basicCount = 0;
                            foreach($blades as $b) {
                                if (($b['type'] ?? 'basic') === 'basic') $basicCount++;
                            }
                            echo $basicCount;
                        @endphp
                    </div>
                    <div class="text-sm text-gray-600">기본 템플릿</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">
                        @php
                            $componentCount = 0;
                            foreach($blades as $b) {
                                if (($b['type'] ?? 'basic') === 'component') $componentCount++;
                            }
                            echo $componentCount;
                        @endphp
                    </div>
                    <div class="text-sm text-gray-600">컴포넌트</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">
                        @php
                            $layoutCount = 0;
                            foreach($blades as $b) {
                                if (($b['type'] ?? 'basic') === 'layout') $layoutCount++;
                            }
                            echo $layoutCount;
                        @endphp
                    </div>
                    <div class="text-sm text-gray-600">레이아웃</div>
                </div>
            </div>
        </div>
    @endif

    <!-- 미리보기 모달 -->
    @if(isset($previewBlade) && $previewBlade)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background: rgba(0,0,0,0.5);">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                미리보기: {{ $previewBlade['title'] ?? '제목 없음' }}
                            </h3>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">{{ ucfirst($previewBlade['type'] ?? 'basic') }}</span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs text-gray-600 mb-2">Blade 템플릿 코드:</div>
                            <pre class="bg-white p-3 rounded border text-xs overflow-x-auto whitespace-pre-wrap font-mono text-gray-800"
                                 style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; max-height: 400px; overflow-y: auto;">{{ $previewContent ?? '&lt;div class="sample-template"&gt;&lt;h1&gt;&#123;&#123; $title &#125;&#125;&lt;/h1&gt;&lt;p&gt;&#123;&#123; $content &#125;&#125;&lt;/p&gt;&lt;/div&gt;' }}
                            </pre>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="$set('previewBlade', null)"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                            닫기
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
