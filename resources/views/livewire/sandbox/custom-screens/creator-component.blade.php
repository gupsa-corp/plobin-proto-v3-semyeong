<div class="max-w-7xl mx-auto p-6">
    <!-- 헤더 -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                ✨ {{ $editMode ? '화면 편집' : '화면 생성기' }}
            </h1>
            <p class="text-gray-600 mt-1">블레이드 템플릿과 라이브와이어를 함수와 연동하여 동작하는 화면을 만들어보세요.</p>
        </div>
        
        <div class="flex space-x-3">
            <button wire:click="cancel" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                취소
            </button>
            <button wire:click="save" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                {{ $editMode ? '수정' : '저장' }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- 왼쪽: 설정 및 함수 연동 -->
        <div class="space-y-6">
            <!-- 기본 정보 -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-4">기본 정보</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">화면 제목</label>
                        <input wire:model="title" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="예: 사용자 대시보드">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">설명</label>
                        <textarea wire:model="description" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="2" placeholder="화면에 대한 설명을 입력하세요"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">유형</label>
                        <select wire:model="type" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="dashboard">대시보드</option>
                            <option value="list">목록</option>
                            <option value="form">폼</option>
                            <option value="detail">상세</option>
                            <option value="report">리포트</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 함수 연동 -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-4">함수 연동</h3>
                
                <div class="space-y-4">
                    <div class="flex space-x-2">
                        <select wire:model="selectedFunction" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">함수를 선택하세요</option>
                            @foreach($availableFunctions as $func)
                                <option value="{{ $func['name'] }}">{{ $func['name'] }}</option>
                            @endforeach
                        </select>
                        <button wire:click="addFunction" class="px-3 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                            추가
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach($connectedFunctions as $index => $func)
                            <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded">
                                <div class="flex-1">
                                    <div class="font-medium text-green-800 text-sm">{{ $func['name'] }}</div>
                                    <div class="text-green-600 text-xs">{{ $func['description'] }}</div>
                                    <input wire:model="connectedFunctions.{{ $index }}.binding" type="text" 
                                           placeholder="라이브와이어 프로퍼티명 (예: users)"
                                           class="mt-1 w-full px-2 py-1 border border-green-300 rounded text-xs">
                                </div>
                                <button wire:click="removeFunction({{ $index }})" class="ml-2 text-red-600 hover:text-red-800">
                                    🗑️
                                </button>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(count($availableFunctions) === 0)
                        <p class="text-sm text-gray-500">사용 가능한 함수가 없습니다. 함수 브라우저에서 함수를 먼저 생성해주세요.</p>
                    @endif
                </div>
            </div>

            <!-- DB 쿼리 -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <h3 class="font-semibold text-gray-900 mb-4">DB 쿼리</h3>
                
                <div class="space-y-4">
                    <div>
                        <input wire:model="newQueryName" type="text" placeholder="쿼리 이름" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm mb-2">
                        <textarea wire:model="newQuerySql" placeholder="SELECT * FROM users WHERE..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" rows="3"></textarea>
                        <button wire:click="addDbQuery" class="mt-2 px-3 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                            쿼리 추가
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach($dbQueries as $index => $query)
                            <div class="p-2 bg-blue-50 border border-blue-200 rounded">
                                <div class="flex items-center justify-between">
                                    <div class="font-medium text-blue-800 text-sm">{{ $query['name'] }}</div>
                                    <button wire:click="removeDbQuery({{ $index }})" class="text-red-600 hover:text-red-800">
                                        🗑️
                                    </button>
                                </div>
                                <div class="text-blue-600 text-xs mt-1">{{ Str::limit($query['sql'], 50) }}</div>
                                <input wire:model="dbQueries.{{ $index }}.binding" type="text" 
                                       placeholder="바인딩할 프로퍼티명"
                                       class="mt-1 w-full px-2 py-1 border border-blue-300 rounded text-xs">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 중앙: 코드 에디터 -->
        <div class="space-y-6">
            <!-- 블레이드 템플릿 -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="font-semibold text-gray-900">블레이드 템플릿</h3>
                </div>
                <div class="p-4">
                    <textarea wire:model="bladeTemplate" class="w-full h-80 px-3 py-2 border border-gray-300 rounded-md font-mono text-sm resize-y" placeholder="블레이드 템플릿 코드를 입력하세요..."></textarea>
                    @error('bladeTemplate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- 라이브와이어 컴포넌트 -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="font-semibold text-gray-900">라이브와이어 컴포넌트</h3>
                </div>
                <div class="p-4">
                    <textarea wire:model="livewireComponent" class="w-full h-80 px-3 py-2 border border-gray-300 rounded-md font-mono text-sm resize-y" placeholder="라이브와이어 컴포넌트 PHP 코드를 입력하세요..."></textarea>
                    @error('livewireComponent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- 오른쪽: 미리보기 -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="border-b border-gray-200 px-4 py-3">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-900">실시간 미리보기</h3>
                        <button wire:click="togglePreview" 
                                class="text-sm px-3 py-1 rounded-md {{ $showPreview ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $showPreview ? '숨기기' : '보기' }}
                        </button>
                    </div>
                </div>
                
                @if($showPreview)
                    <div class="p-4">
                        <div class="border rounded-lg p-4 bg-gray-50 min-h-[400px] overflow-auto">
                            @if(!empty($bladeTemplate))
                                @livewire('sandbox.custom-screens.renderer.component', ['screenData' => [
                                    'title' => $title ?: '미리보기',
                                    'description' => $description,
                                    'type' => $type,
                                    'blade_template' => $bladeTemplate,
                                    'connected_functions' => json_encode($connectedFunctions),
                                    'db_queries' => json_encode($dbQueries)
                                ]], key('preview-'.now()->timestamp))
                            @else
                                <div class="text-center text-gray-500 py-8">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p>블레이드 템플릿을 입력하면<br>실시간 미리보기가 나타납니다.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- 도움말 -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">💡 사용 팁</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• 함수 연동: 함수 브라우저의 함수를 선택하여 데이터를 가져올 수 있습니다</li>
                    <li>• 바인딩: 라이브와이어 프로퍼티명을 지정하여 템플릿에서 사용하세요</li>
                    <li>• 변수 사용: 템플릿에서 &#123;&#123; $변수명 &#125;&#125;으로 데이터를 출력할 수 있습니다</li>
                    <li>• 루프: &#64;foreach($users as $user) ... &#64;endforeach로 반복 출력</li>
                    <li>• 조건: &#64;if($condition) ... &#64;endif로 조건부 렌더링</li>
                </ul>
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