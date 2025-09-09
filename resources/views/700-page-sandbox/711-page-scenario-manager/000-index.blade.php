<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include('700-page-sandbox.700-common.301-layout-head', ['title' => '시나리오 관리자'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')

    <div class="min-h-screen w-full">
        <div class="p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">📋 시나리오 관리자</h1>
            <p class="text-gray-600 mb-8">개발 시나리오와 요구사항을 체계적으로 관리하세요</p>
            {{-- @livewire('sandbox.scenario-manager') --}}

            <!-- 임시 Alpine.js 버전으로 교체 -->
            <div x-data="scenarioManager()" class="space-y-6">
                <!-- 성공 메시지 -->
                <div x-show="message" x-text="message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" x-transition></div>

                <!-- 탭 네비게이션 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1 mb-6">
                    <nav class="flex space-x-1">
                        <button @click="activeTab = 'list'"
                                :class="activeTab === 'list'
                                    ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md transform scale-105'
                                    : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'"
                                class="flex-1 flex items-center justify-center px-6 py-3 rounded-lg font-medium text-sm transition-all duration-200 ease-in-out">
                            <span class="text-lg mr-2">📋</span>
                            <span class="hidden sm:inline">시나리오 목록</span>
                            <span class="sm:hidden">목록</span>
                        </button>
                        <button @click="activeTab = 'create'"
                                :class="activeTab === 'create'
                                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md transform scale-105'
                                    : 'text-gray-600 hover:text-green-600 hover:bg-green-50'"
                                class="flex-1 flex items-center justify-center px-6 py-3 rounded-lg font-medium text-sm transition-all duration-200 ease-in-out">
                            <span class="text-lg mr-2">✨</span>
                            <span class="hidden sm:inline">새 시나리오</span>
                            <span class="sm:hidden">생성</span>
                        </button>
                        <button x-show="selectedScenarioId"
                                @click="activeTab = 'detail'"
                                :class="activeTab === 'detail'
                                    ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-md transform scale-105'
                                    : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50'"
                                class="flex-1 flex items-center justify-center px-6 py-3 rounded-lg font-medium text-sm transition-all duration-200 ease-in-out">
                            <span class="text-lg mr-2">📝</span>
                            <span class="hidden sm:inline">시나리오 상세</span>
                            <span class="sm:hidden">상세</span>
                        </button>
                    </nav>
                </div>

                <!-- 시나리오 상세 탭 -->
                <div x-show="activeTab === 'detail' && selectedScenario" class="space-y-6">
                    <!-- 시나리오 정보 수정 -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">시나리오 상세 정보</h2>
                            <button @click="activeTab = 'list'"
                                    class="text-gray-500 hover:text-gray-700">
                                ← 목록으로 돌아가기
                            </button>
                        </div>

                        <form @submit.prevent="updateScenario" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">제목</label>
                                    <input x-model="selectedScenario.title"
                                           type="text"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">상태</label>
                                    <select x-model="selectedScenario.status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="todo">할 일</option>
                                        <option value="in-progress">진행중</option>
                                        <option value="done">완료</option>
                                        <option value="cancelled">취소됨</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">설명</label>
                                <textarea x-model="selectedScenario.description"
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">우선순위</label>
                                <select x-model="selectedScenario.priority"
                                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="low">낮음</option>
                                    <option value="medium">보통</option>
                                    <option value="high">높음</option>
                                </select>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    업데이트
                                </button>
                                <button type="button"
                                        @click="deleteScenario(selectedScenario.id)"
                                        onclick="return confirm('정말 삭제하시겠습니까?')"
                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                    삭제
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 요구사항 관리 -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">요구사항</h3>

                        <!-- 새 요구사항 추가 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-md">
                            <form @submit.prevent="addRequirement" class="space-y-3">
                                <div>
                                    <input x-model="newRequirement.content"
                                           type="text"
                                           placeholder="새 요구사항을 입력하세요..."
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                                    요구사항 추가
                                </button>
                            </form>
                        </div>

                        <!-- 요구사항 목록 -->
                        <div class="space-y-2">
                            <template x-for="requirement in selectedScenario.requirements" :key="requirement.id">
                                <div class="border border-gray-200 rounded-md p-3">
                                    <div class="flex items-center gap-3">
                                        <input @change="toggleRequirement(requirement.id)"
                                               :checked="requirement.completed"
                                               type="checkbox"
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <span class="flex-1"
                                              :class="requirement.completed ? 'line-through text-gray-500' : ''"
                                              x-text="requirement.content">
                                        </span>
                                        <button @click="deleteRequirement(requirement.id)"
                                                onclick="return confirm('이 요구사항을 삭제하시겠습니까?')"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            삭제
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="!selectedScenario.requirements || selectedScenario.requirements.length === 0"
                                 class="text-center py-8 text-gray-500">
                                <div class="text-4xl mb-2">📝</div>
                                <p>아직 요구사항이 없습니다</p>
                                <p class="text-sm">위 폼을 사용해서 첫 번째 요구사항을 추가해보세요</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 시나리오 생성 탭 -->
                <div x-show="activeTab === 'create'" class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">새 시나리오 생성</h2>

                    <form @submit.prevent="createScenario" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">제목 *</label>
                            <input x-model="newScenario.title"
                                   type="text"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="예: RFx 문서 저장 기능">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">설명</label>
                            <textarea x-model="newScenario.description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="시나리오에 대한 자세한 설명을 입력하세요..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">우선순위</label>
                            <select x-model="newScenario.priority"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="low">낮음</option>
                                <option value="medium">보통</option>
                                <option value="high">높음</option>
                            </select>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                시나리오 생성
                            </button>
                            <button type="button"
                                    @click="activeTab = 'list'"
                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                취소
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 시나리오 목록 탭 -->
                <div x-show="activeTab === 'list'" class="space-y-6">
                    <!-- 검색 및 필터 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input x-model.live="searchTerm"
                                       type="text"
                                       placeholder="시나리오 제목이나 설명 검색..."
                                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-gray-50 focus:bg-white transition-all duration-200">
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                <div class="relative">
                                    <select x-model.live="statusFilter"
                                            class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-3 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm min-w-[120px]">
                                        <option value="all">📊 모든 상태</option>
                                        <option value="todo">⏳ 할 일</option>
                                        <option value="in-progress">🔄 진행중</option>
                                        <option value="done">✅ 완료</option>
                                        <option value="cancelled">❌ 취소됨</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="relative">
                                    <select x-model.live="priorityFilter"
                                            class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-3 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm min-w-[120px]">
                                        <option value="all">🎯 모든 우선순위</option>
                                        <option value="high">🔴 높음</option>
                                        <option value="medium">🟡 보통</option>
                                        <option value="low">🟢 낮음</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 필터 결과 표시 -->
                        <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                            <div class="flex items-center space-x-4">
                                <span x-show="searchTerm" class="flex items-center">
                                    <span class="font-medium">검색어:</span>
                                    <span class="ml-1 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs" x-text="searchTerm"></span>
                                </span>
                                <span x-show="statusFilter !== 'all'" class="flex items-center">
                                    <span class="font-medium">상태:</span>
                                    <span class="ml-1 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs"
                                          x-text="statusFilter === 'todo' ? '할 일' : statusFilter === 'in-progress' ? '진행중' : statusFilter === 'done' ? '완료' : '취소됨'"></span>
                                </span>
                                <span x-show="priorityFilter !== 'all'" class="flex items-center">
                                    <span class="font-medium">우선순위:</span>
                                    <span class="ml-1 px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs"
                                          x-text="priorityFilter === 'high' ? '높음' : priorityFilter === 'medium' ? '보통' : '낮음'"></span>
                                </span>
                            </div>
                            <div class="text-xs text-gray-500">
                                총 <span class="font-semibold text-gray-700" x-text="scenarios.length"></span>개 시나리오
                            </div>
                        </div>
                    </div>

                    <!-- 시나리오 카드 목록 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="scenario in scenarios" :key="scenario.id">
                            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <!-- 편집 모드 토글 -->
                                    <div class="flex justify-between items-center mb-4">
                                        <div x-show="!scenario.editing" class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-800" x-text="scenario.title"></h3>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="scenario.editing ? cancelScenarioEdit(scenario) : startScenarioEdit(scenario)"
                                                    :class="scenario.editing
                                                        ? 'bg-gradient-to-r from-gray-500 to-gray-600 text-white shadow-sm hover:from-gray-600 hover:to-gray-700'
                                                        : 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm hover:from-blue-600 hover:to-blue-700'"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 flex items-center space-x-1">
                                                <svg x-show="!scenario.editing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <svg x-show="scenario.editing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span x-text="scenario.editing ? '취소' : '수정'"></span>
                                            </button>
                                            <button x-show="!scenario.editing" @click="selectScenario(scenario.id)"
                                                    class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>상세</span>
                                            </button>
                                            <button @click="deleteScenario(scenario.id)"
                                                    onclick="return confirm('정말 삭제하시겠습니까?')"
                                                    class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105 flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>삭제</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- 편집 모드 -->
                                    <div x-show="scenario.editing" class="space-y-4 border-t pt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">제목</label>
                                            <input x-model="scenario.title"
                                                   type="text"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">설명</label>
                                            <textarea x-model="scenario.description"
                                                      rows="2"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                                      placeholder="시나리오 설명을 입력하세요..."></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">우선순위</label>
                                                <select x-model="scenario.priority"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                    <option value="low">낮음</option>
                                                    <option value="medium">보통</option>
                                                    <option value="high">높음</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">상태</label>
                                                <select x-model="scenario.status"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                    <option value="todo">할 일</option>
                                                    <option value="in-progress">진행중</option>
                                                    <option value="done">완료</option>
                                                    <option value="cancelled">취소됨</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 pt-2">
                                            <button @click="saveScenarioEdit(scenario)"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                                저장
                                            </button>
                                            <button @click="cancelScenarioEdit(scenario)"
                                                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 text-sm">
                                                취소
                                            </button>
                                        </div>
                                    </div>

                                    <!-- 보기 모드 -->
                                    <div x-show="!scenario.editing">
                                        <!-- 우선순위 -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="px-2 py-1 text-xs font-medium rounded"
                                                  :class="getPriorityClass(scenario.priority)"
                                                  x-text="scenario.priority === 'high' ? '높음' : scenario.priority === 'medium' ? '보통' : '낮음'">
                                            </span>
                                            <span class="px-2 py-1 text-xs font-medium rounded"
                                                  :class="getStatusClass(scenario.status)"
                                                  x-text="scenario.status === 'todo' ? '할 일' : scenario.status === 'in-progress' ? '진행중' : scenario.status === 'done' ? '완료' : '취소됨'">
                                            </span>
                                        </div>

                                        <!-- 설명 -->
                                        <div x-show="scenario.description" class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="scenario.description"></div>

                                        <!-- 진행률 -->
                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                <span>진행률</span>
                                                <span x-text="scenario.progress + '%'"></span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${scenario.progress}%`"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- 시나리오가 없을 때 -->
                        <div x-show="scenarios.length === 0" class="col-span-full text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">📋</div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">시나리오가 없습니다</h3>
                            <p class="text-gray-500 mb-4">첫 번째 개발 시나리오를 생성해보세요</p>
                            <button @click="activeTab = 'create'"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                시나리오 생성하기
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- 수동 Livewire 스크립트 로드 (Laravel 11 호환성) -->
    <script>
        // Livewire가 로드될 때까지 기다림
        function waitForLivewire(callback, maxAttempts = 50) {
            let attempts = 0;

            const checkLivewire = () => {
                attempts++;

                if (typeof window.livewire !== 'undefined') {
                    console.log('Livewire loaded successfully');
                    callback();
                } else if (attempts < maxAttempts) {
                    setTimeout(checkLivewire, 100);
                } else {
                    console.error('Livewire failed to load after', maxAttempts, 'attempts');
                    // Livewire가 로드되지 않은 경우 수동으로 로드
                    loadLivewireManually(callback);
                }
            };

            checkLivewire();
        }

        function loadLivewireManually(callback) {
            console.log('Loading Livewire manually...');

            // 기존 스크립트가 있는지 확인
            const existingScript = document.querySelector('script[src*="livewire"]');
            if (!existingScript) {
                const script = document.createElement('script');
                script.src = '/livewire/livewire.js';
                script.onload = function() {
                    console.log('Livewire script loaded manually');
                    if (callback) callback();
                };
                script.onerror = function() {
                    console.error('Failed to load Livewire script');
                };
                document.head.appendChild(script);
            } else {
                console.log('Livewire script already exists');
                if (callback) callback();
            }
        }

        // 페이지 로드 시 초기화
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, Alpine.js components ready');
        });

        // Alpine.js 시나리오 관리자 함수
        function scenarioManager() {
            return {
                activeTab: 'list',
                selectedScenarioId: null,
                selectedScenario: null,
                message: '',
                newScenario: {
                    title: '',
                    description: '',
                    priority: 'medium'
                },
                newRequirement: {
                    content: ''
                },
                scenarios: [
                    {
                        id: 1,
                        title: '사용자 인증 시스템 구현',
                        description: 'JWT 기반 사용자 인증 및 권한 관리 시스템을 구현합니다.',
                        priority: 'high',
                        status: 'in-progress',
                        progress: 40,
                        editing: false,
                        originalData: null,
                        requirements: [
                            { id: 1, content: '로그인 API 구현', completed: true },
                            { id: 2, content: '회원가입 API 구현', completed: false },
                            { id: 3, content: 'JWT 토큰 발급 및 검증', completed: false }
                        ]
                    },
                    {
                        id: 2,
                        title: '파일 업로드 및 관리 시스템',
                        description: '안전한 파일 업로드, 저장, 조회 시스템을 구현합니다.',
                        priority: 'medium',
                        status: 'todo',
                        progress: 0,
                        editing: false,
                        originalData: null,
                        requirements: [
                            { id: 1, content: '파일 업로드 API 구현', completed: false },
                            { id: 2, content: '파일 타입 검증 로직', completed: false }
                        ]
                    }
                ],

                createScenario() {
                    if (!this.newScenario.title.trim()) {
                        alert('제목을 입력해주세요.');
                        return;
                    }

                    // 새 시나리오 생성 (실제로는 API 호출)
                    const newId = Math.max(...this.scenarios.map(s => s.id)) + 1;
                    this.scenarios.push({
                        id: newId,
                        title: this.newScenario.title,
                        description: this.newScenario.description,
                        priority: this.newScenario.priority,
                        status: 'todo',
                        progress: 0
                    });

                    // 폼 초기화
                    this.newScenario = { title: '', description: '', priority: 'medium' };
                    this.activeTab = 'list';
                    this.message = '시나리오가 생성되었습니다.';

                    // 메시지 자동 제거
                    setTimeout(() => this.message = '', 3000);
                },

                selectScenario(id) {
                    this.selectedScenarioId = id;
                    this.selectedScenario = this.scenarios.find(s => s.id === parseInt(id));
                    this.activeTab = 'detail';
                },

                updateScenarioStatus(id, status) {
                    const scenario = this.scenarios.find(s => s.id === parseInt(id));
                    if (scenario) {
                        scenario.status = status;
                        this.message = '상태가 업데이트되었습니다.';
                        setTimeout(() => this.message = '', 2000);
                    }
                },

                startScenarioEdit(scenario) {
                    // 편집 시작 시 원본 데이터 백업
                    scenario.originalData = {
                        title: scenario.title,
                        description: scenario.description,
                        priority: scenario.priority,
                        status: scenario.status
                    };
                    scenario.editing = true;
                },

                saveScenarioEdit(scenario) {
                    if (!scenario.title.trim()) {
                        alert('제목을 입력해주세요.');
                        return;
                    }

                    // 편집 모드 종료 및 원본 데이터 정리
                    scenario.editing = false;
                    scenario.originalData = null;

                    // 메시지 표시
                    this.message = '시나리오가 업데이트되었습니다.';
                    setTimeout(() => this.message = '', 3000);
                },

                cancelScenarioEdit(scenario) {
                    // 원본 데이터로 복원
                    if (scenario.originalData) {
                        Object.assign(scenario, scenario.originalData);
                    }

                    // 편집 모드 종료 및 원본 데이터 정리
                    scenario.editing = false;
                    scenario.originalData = null;
                },

                updateScenario() {
                    if (!this.selectedScenario.title.trim()) {
                        alert('제목을 입력해주세요.');
                        return;
                    }

                    // 시나리오 업데이트 (실제로는 API 호출)
                    const index = this.scenarios.findIndex(s => s.id === this.selectedScenario.id);
                    if (index !== -1) {
                        this.scenarios[index] = { ...this.selectedScenario };
                        this.message = '시나리오가 업데이트되었습니다.';
                        setTimeout(() => this.message = '', 3000);
                    }
                },

                addRequirement() {
                    if (!this.newRequirement.content.trim()) {
                        alert('요구사항 내용을 입력해주세요.');
                        return;
                    }

                    if (!this.selectedScenario.requirements) {
                        this.selectedScenario.requirements = [];
                    }

                    const newReqId = Math.max(...this.selectedScenario.requirements.map(r => r.id || 0), 0) + 1;
                    this.selectedScenario.requirements.push({
                        id: newReqId,
                        content: this.newRequirement.content,
                        completed: false
                    });

                    this.newRequirement.content = '';
                    this.message = '요구사항이 추가되었습니다.';
                    setTimeout(() => this.message = '', 2000);
                },

                toggleRequirement(requirementId) {
                    const requirement = this.selectedScenario.requirements.find(r => r.id === parseInt(requirementId));
                    if (requirement) {
                        requirement.completed = !requirement.completed;
                        this.message = requirement.completed ? '요구사항이 완료되었습니다.' : '요구사항이 미완료로 변경되었습니다.';
                        setTimeout(() => this.message = '', 2000);
                    }
                },

                deleteRequirement(requirementId) {
                    if (confirm('이 요구사항을 삭제하시겠습니까?')) {
                        this.selectedScenario.requirements = this.selectedScenario.requirements.filter(r => r.id !== parseInt(requirementId));
                        this.message = '요구사항이 삭제되었습니다.';
                        setTimeout(() => this.message = '', 2000);
                    }
                },

                deleteScenario(id) {
                    if (confirm('정말 삭제하시겠습니까?')) {
                        this.scenarios = this.scenarios.filter(s => s.id !== parseInt(id));
                        if (this.selectedScenarioId === parseInt(id)) {
                            this.selectedScenarioId = null;
                            this.selectedScenario = null;
                            this.activeTab = 'list';
                        }
                        this.message = '시나리오가 삭제되었습니다.';
                        setTimeout(() => this.message = '', 2000);
                    }
                },

                getPriorityClass(priority) {
                    const classes = {
                        high: 'priority-high',
                        medium: 'priority-medium',
                        low: 'priority-low'
                    };
                    return classes[priority] || 'priority-medium';
                },

                getStatusClass(status) {
                    const classes = {
                        todo: 'status-todo',
                        'in-progress': 'status-in-progress',
                        done: 'status-done',
                        cancelled: 'status-cancelled'
                    };
                    return classes[status] || 'status-todo';
                }
            }
        }
    </script>

    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>
