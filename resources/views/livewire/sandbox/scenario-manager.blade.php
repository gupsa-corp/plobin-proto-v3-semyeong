<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- 헤더 -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">🎯 시나리오 관리</h1>
            <p class="text-gray-600 text-sm">큰 목표를 설정하고 단계별로 세부 실행 계획을 관리하세요</p>
        </div>

        <!-- 메시지 -->
        @if($message)
            <div class="mb-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                {{ $message }}
            </div>
        @endif

        <!-- 빠른 액션 바 -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button @click="activeTab = 'list'"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-medium">
                        📋 목록 보기
                    </button>
                    <button @click="activeTab = 'create'"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm font-medium">
                        ➕ 새 시나리오
                    </button>
                    <div class="text-sm text-gray-500">
                        총 {{ $scenarios->count() }}개 시나리오
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <input wire:model.live.debounce.300ms="searchTerm"
                           type="text"
                           placeholder="시나리오 검색..."
                           class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- 시나리오 목록 뷰 -->
        <div x-show="activeTab === 'list'" class="space-y-6">

            <!-- 뷰 제목 -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">📋 시나리오 목록</h2>
                <p class="text-sm text-gray-600">프로젝트의 모든 시나리오들을 한눈에 확인하고 관리하세요</p>
            </div>

            <!-- 시나리오 카드 목록 -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($scenarios as $scenario)
                    <div class="bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer"
                         wire:click="selectScenario({{ $scenario->id }})">

                        <div class="p-5">
                            <!-- 제목과 상태 -->
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-semibold text-gray-900 text-sm leading-tight flex-1 mr-2">{{ $scenario->title }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full shrink-0
                                    @if($scenario->status === 'done') bg-green-100 text-green-800
                                    @elseif($scenario->status === 'in-progress') bg-blue-100 text-blue-800
                                    @elseif($scenario->status === 'review') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @switch($scenario->status)
                                        @case('backlog') 백로그 @break
                                        @case('todo') 할 일 @break
                                        @case('in-progress') 진행중 @break
                                        @case('review') 검토 @break
                                        @case('done') 완료 @break
                                        @case('cancelled') 취소 @break
                                        @default 할 일
                                    @endswitch
                                </span>
                            </div>

                            <!-- 설명 -->
                            @if($scenario->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($scenario->description, 80) }}</p>
                            @endif

                            <!-- 그룹과 우선순위 -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                      style="background-color: {{ $scenario->group->color }}20; color: {{ $scenario->group->color }}">
                                    {{ $scenario->group->name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    @switch($scenario->priority)
                                        @case('critical') 🔥 긴급 @break
                                        @case('high') ⚡ 높음 @break
                                        @case('medium') 📊 보통 @break
                                        @case('low') 🐌 낮음 @break
                                    @endswitch
                                </span>
                            </div>

                            <!-- 진행률 바 -->
                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>진행률</span>
                                    <span>{{ $scenario->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-300"
                                         style="width: {{ $scenario->progress_percentage }}%"></div>
                                </div>
                            </div>

                            <!-- 하단 정보 -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $scenario->subScenarios->count() }}개 세부 목표</span>
                                <span>{{ $scenario->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- 시나리오가 없을 때 -->
                @if($scenarios->isEmpty())
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">🎯</div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">아직 시나리오가 없습니다</h3>
                        <p class="text-gray-500 mb-6">첫 번째 목표를 설정해보세요</p>
                        <button wire:click="$set('activeTab', 'create')"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                            🎯 새 시나리오 만들기
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- 세부 목표 상세 뷰 -->
        <div x-show="selectedSubScenario && !selectedStep" class="space-y-6">

            <!-- 뷰 제목 -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">🎯 세부 목표 상세</h2>
                <p class="text-sm text-gray-600">선택한 세부 목표의 세부 단계들을 관리하고 진행 상황을 확인하세요</p>
            </div>

            <!-- 세부 목표 헤더 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <h2 class="text-xl font-bold text-gray-900">{{ $selectedSubScenario->title }}</h2>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            @if($selectedSubScenario->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($selectedSubScenario->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            @switch($selectedSubScenario->priority)
                                @case('high') ⚡ 높음 @break
                                @case('medium') 📊 보통 @break
                                @case('low') 🐌 낮음 @break
                            @endswitch
                        </span>
                    </div>
                </div>

                @if($selectedSubScenario->description)
                    <p class="text-gray-600 mb-4">{{ $selectedSubScenario->description }}</p>
                @endif

                <!-- 메타 정보 -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">상태</span>
                        <div class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @if($selectedSubScenario->status === 'done') bg-green-100 text-green-800
                                @elseif($selectedSubScenario->status === 'in-progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($selectedSubScenario->status)
                                    @case('todo') 할 일 @break
                                    @case('in-progress') 진행중 @break
                                    @case('done') 완료 @break
                                    @case('cancelled') 취소 @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">진행률</span>
                        <div class="mt-1 text-sm font-semibold text-gray-900">{{ $selectedSubScenario->progress_percentage }}%</div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">단계 수</span>
                        <div class="mt-1 text-sm text-gray-600">{{ $selectedSubScenario->steps->count() }}개</div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">생성일</span>
                        <div class="mt-1 text-sm text-gray-600">{{ $selectedSubScenario->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                <!-- 진행률 바 -->
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                             style="width: {{ $selectedSubScenario->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- 세부 단계들 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">📝 세부 단계들</h3>
                    <span class="text-sm text-gray-500">{{ $selectedSubScenario->steps->count() }}개</span>
                </div>

                @if($selectedSubScenario->steps->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-4xl mb-3">📝</div>
                        <h4 class="text-base font-medium text-gray-700 mb-2">아직 세부 단계가 없습니다</h4>
                        <p class="text-gray-500 text-sm">세부 목표를 작은 단계들로 나누어보세요</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($selectedSubScenario->steps as $step)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all cursor-pointer"
                                 wire:click="selectStep({{ $step->id }})">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-3">
                                        <span class="w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ $step->step_number }}
                                        </span>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $step->title }}</h4>
                                            <span class="text-blue-600 text-xs">👆 클릭하여 상세보기</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        @if($step->status === 'done') bg-green-100 text-green-800
                                        @elseif($step->status === 'in-progress') bg-blue-100 text-blue-800
                                        @elseif($step->status === 'blocked') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @switch($step->status)
                                            @case('todo') 할 일 @break
                                            @case('in-progress') 진행중 @break
                                            @case('done') 완료 @break
                                            @case('blocked') 차단됨 @break
                                        @endswitch
                                    </span>
                                </div>

                                @if($step->description)
                                    <p class="text-sm text-gray-600 mt-2">{{ $step->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- 단계 상세 뷰 -->
        <div x-show="selectedStep" class="space-y-6">

            <!-- 뷰 제목 -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">📋 단계 상세</h2>
                <p class="text-sm text-gray-600">선택한 단계의 상세 정보와 실행 계획을 확인하세요</p>
            </div>

            <!-- 단계 헤더 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-10 h-10 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-lg font-medium">
                            {{ $selectedStep->step_number }}
                        </span>
                        <h2 class="text-xl font-bold text-gray-900">{{ $selectedStep->title }}</h2>
                    </div>
                </div>

                @if($selectedStep->description)
                    <p class="text-gray-600 mb-4">{{ $selectedStep->description }}</p>
                @endif

                <!-- 메타 정보 -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">상태</span>
                        <div class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @if($selectedStep->status === 'done') bg-green-100 text-green-800
                                @elseif($selectedStep->status === 'in-progress') bg-blue-100 text-blue-800
                                @elseif($selectedStep->status === 'blocked') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($selectedStep->status)
                                    @case('todo') 할 일 @break
                                    @case('in-progress') 진행중 @break
                                    @case('done') 완료 @break
                                    @case('blocked') 차단됨 @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">단계 번호</span>
                        <div class="mt-1 text-sm font-semibold text-gray-900">{{ $selectedStep->step_number }}</div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">예상 시간</span>
                        <div class="mt-1 text-sm text-gray-600">{{ $selectedStep->estimated_hours ? $selectedStep->estimated_hours . '시간' : '미정' }}</div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">생성일</span>
                        <div class="mt-1 text-sm text-gray-600">{{ $selectedStep->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                <!-- 완료된 경우 완료일 표시 -->
                @if($selectedStep->status === 'done' && $selectedStep->completed_at)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-green-600">✅</span>
                            <span class="text-sm font-medium text-green-800">완료됨</span>
                            <span class="text-sm text-green-600">{{ $selectedStep->completed_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 의존 관계 -->
            @if($selectedStep->dependencies && count($selectedStep->dependencies) > 0)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔗 선행 단계</h3>
                    <div class="space-y-2">
                        @foreach($selectedStep->dependencies as $depId)
                            @php
                                $depStep = \App\Models\SandboxScenarioStep::find($depId);
                            @endphp
                            @if($depStep)
                                <div class="flex items-center space-x-3 text-sm">
                                    <span class="w-6 h-6 bg-yellow-100 text-yellow-800 rounded-full flex items-center justify-center text-xs font-medium">
                                        {{ $depStep->step_number }}
                                    </span>
                                    <span class="text-gray-700">{{ $depStep->title }}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        @if($depStep->status === 'done') bg-green-100 text-green-800
                                        @elseif($depStep->status === 'in-progress') bg-blue-100 text-blue-800
                                        @elseif($depStep->status === 'blocked') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @switch($depStep->status)
                                            @case('todo') 할 일 @break
                                            @case('in-progress') 진행중 @break
                                            @case('done') 완료 @break
                                            @case('blocked') 차단됨 @break
                                        @endswitch
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- 시나리오 생성 뷰 -->
        <div x-show="activeTab === 'create'" class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">🎯 시나리오 생성</h2>
                    <p class="text-sm text-gray-600">새로운 프로젝트 시나리오를 만들고 기본 정보를 설정하세요</p>
                </div>
                <button wire:click="$set('activeTab', 'list')"
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="createScenario" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">그룹 선택</label>
                        <select wire:model="newScenario.group_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">그룹을 선택하세요</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('newScenario.group_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">우선순위</label>
                        <select wire:model="newScenario.priority"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="medium">📊 보통</option>
                            <option value="high">⚡ 높음</option>
                            <option value="critical">🔥 긴급</option>
                            <option value="low">🐌 낮음</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">시나리오 제목</label>
                    <input wire:model="newScenario.title"
                           type="text"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="예: 사용자 인증 시스템 개발">
                    @error('newScenario.title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">설명 (선택사항)</label>
                    <textarea wire:model="newScenario.description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                              placeholder="시나리오에 대한 간단한 설명을 입력하세요..."></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium">
                        ✅ 시나리오 생성
                    </button>
                    <button type="button"
                            wire:click="$set('activeTab', 'list')"
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 font-medium">
                        취소
                    </button>
                </div>
            </form>
        </div>

        <!-- 브레드크럼 네비게이션 -->
        <div x-show="breadcrumb.length > 0" class="bg-white rounded-lg border p-4 mb-6">
            <nav class="flex items-center space-x-2 text-sm">
                <button wire:click="goBackToList" class="text-blue-600 hover:text-blue-800 font-medium">
                    📋 시나리오 목록
                </button>
                <span class="text-gray-400">/</span>
                @foreach($breadcrumb as $index => $crumb)
                    <button wire:click="navigateToBreadcrumb({{ $index }})"
                            class="text-blue-600 hover:text-blue-800 {{ $loop->last ? 'font-semibold' : '' }}">
                        @if($crumb['type'] === 'scenario') 🎯 @elseif($crumb['type'] === 'sub-scenario') 🎯 @else 📝 @endif
                        {{ $crumb['title'] }}
                    </button>
                    @if(!$loop->last)
                        <span class="text-gray-400">/</span>
                    @endif
                @endforeach
            </nav>
        </div>

        <!-- 시나리오 상세 뷰 -->
        <div x-show="activeTab === 'detail' && selectedScenario && !selectedSubScenario && !selectedStep" class="space-y-6">

            <!-- 뷰 제목 -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">📝 시나리오 상세</h2>
                <p class="text-sm text-gray-600">선택한 시나리오의 전체 구조와 진행 상황을 확인하세요</p>
            </div>

            <!-- 시나리오 헤더 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <h2 class="text-xl font-bold text-gray-900">{{ $selectedScenario->title }}</h2>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            @if($selectedScenario->priority === 'critical') bg-red-100 text-red-800
                            @elseif($selectedScenario->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($selectedScenario->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            @switch($selectedScenario->priority)
                                @case('critical') 🔥 긴급 @break
                                @case('high') ⚡ 높음 @break
                                @case('medium') 📊 보통 @break
                                @case('low') 🐌 낮음 @break
                            @endswitch
                        </span>
                    </div>
                    <button wire:click="goBackToList"
                            class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                @if($selectedScenario->description)
                    <p class="text-gray-600 mb-4">{{ $selectedScenario->description }}</p>
                @endif

                <!-- 메타 정보 -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">그룹</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                  style="background-color: {{ $selectedScenario->group->color }}20; color: {{ $selectedScenario->group->color }}">
                                {{ $selectedScenario->group->name }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">상태</span>
                        <div class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @if($selectedScenario->status === 'done') bg-green-100 text-green-800
                                @elseif($selectedScenario->status === 'in-progress') bg-blue-100 text-blue-800
                                @elseif($selectedScenario->status === 'review') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($selectedScenario->status)
                                    @case('backlog') 백로그 @break
                                    @case('todo') 할 일 @break
                                    @case('in-progress') 진행중 @break
                                    @case('review') 검토 @break
                                    @case('done') 완료 @break
                                    @case('cancelled') 취소 @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">진행률</span>
                        <div class="mt-1 text-sm font-semibold text-gray-900">{{ $selectedScenario->progress_percentage }}%</div>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">생성일</span>
                        <div class="mt-1 text-sm text-gray-600">{{ $selectedScenario->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                <!-- 진행률 바 -->
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                             style="width: {{ $selectedScenario->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- 세부 목표들 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🎯 세부 목표들</h3>
                    <span class="text-sm text-gray-500">{{ $selectedScenario->subScenarios->count() }}개</span>
                </div>

                @if($selectedScenario->subScenarios->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-4xl mb-3">📝</div>
                        <h4 class="text-base font-medium text-gray-700 mb-2">아직 세부 목표가 없습니다</h4>
                        <p class="text-gray-500 text-sm">큰 목표를 작은 단계들로 나누어보세요</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($selectedScenario->subScenarios as $subScenario)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all cursor-pointer"
                                 wire:click="selectSubScenario({{ $subScenario->id }})">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center space-x-2 flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $subScenario->title }}</h4>
                                        <span class="text-blue-600 text-xs">👆 클릭하여 상세보기</span>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded
                                            @if($subScenario->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($subScenario->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @switch($subScenario->priority)
                                                @case('high') ⚡ @break
                                                @case('medium') 📊 @break
                                                @case('low') 🐌 @break
                                            @endswitch
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded
                                            @if($subScenario->status === 'done') bg-green-100 text-green-800
                                            @elseif($subScenario->status === 'in-progress') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @switch($subScenario->status)
                                                @case('todo') 할 일 @break
                                                @case('in-progress') 진행중 @break
                                                @case('done') 완료 @break
                                                @case('cancelled') 취소 @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                @if($subScenario->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $subScenario->description }}</p>
                                @endif

                                <!-- 세부 단계들 미리보기 -->
                                @if($subScenario->steps->isNotEmpty())
                                    <div class="space-y-2">
                                        @foreach($subScenario->steps->take(3) as $step)
                                            <div class="flex items-center space-x-3 text-sm pl-4 border-l-2 border-gray-200">
                                                <span class="w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs font-medium">
                                                    {{ $step->step_number }}
                                                </span>
                                                <span class="flex-1 {{ $step->status === 'done' ? 'line-through text-gray-500' : '' }}">
                                                    {{ $step->title }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-medium rounded
                                                    @if($step->status === 'done') bg-green-100 text-green-800
                                                    @elseif($step->status === 'in-progress') bg-blue-100 text-blue-800
                                                    @elseif($step->status === 'blocked') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @switch($step->status)
                                                        @case('todo') 할 일 @break
                                                        @case('in-progress') 진행중 @break
                                                        @case('done') 완료 @break
                                                        @case('blocked') 차단됨 @break
                                                    @endswitch
                                                </span>
                                            </div>
                                        @endforeach
                                        @if($subScenario->steps->count() > 3)
                                            <div class="text-xs text-gray-500 pl-4 border-l-2 border-gray-200">
                                                + {{ $subScenario->steps->count() - 3 }}개의 단계 더 보기...
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500 pl-4 border-l-2 border-gray-200">
                                        아직 세부 단계가 없습니다
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Alpine.js 초기화 -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scenarioManager', () => ({
                activeTab: '{{ $activeTab }}',
                selectedScenarioId: {{ $selectedScenarioId ?: 'null' }}
            }));
        });
    </script>
</div>
