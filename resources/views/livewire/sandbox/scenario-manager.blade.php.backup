<style>
.priority-high {
    background-color: #fef2f2;
    color: #dc2626;
}
.priority-medium {
    background-color: #fef3c7;
    color: #d97706;
}
.priority-low {
    background-color: #f0fdf4;
    color: #16a34a;
}
.status-todo {
    background-color: #f3f4f6;
    color: #374151;
}
.status-in-progress {
    background-color: #dbeafe;
    color: #2563eb;
}
.status-done {
    background-color: #dcfce7;
    color: #16a34a;
}
.status-cancelled {
    background-color: #fee2e2;
    color: #dc2626;
}
.requirement-completed {
    text-decoration: line-through;
    color: #9ca3af;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<div x-data="scenarioManager()" class="space-y-6">
    <!-- 성공/에러 메시지 -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- 탭 네비게이션 -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="setActiveTab('list')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'list' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                📋 시나리오 목록
            </button>
            <button wire:click="setActiveTab('create')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'create' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                ➕ 새 시나리오
            </button>
            @if($selectedScenarioId)
            <button wire:click="setActiveTab('detail')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'detail' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                📝 시나리오 상세
            </button>
            @endif
        </nav>
    </div>

    <!-- 시나리오 목록 탭 -->
    @if($activeTab === 'list')
        <div class="space-y-4">
            <!-- 검색 및 필터 -->
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               wire:model.live="searchTerm"
                               placeholder="시나리오 제목이나 설명 검색..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">모든 상태</option>
                            <option value="todo">할 일</option>
                            <option value="in-progress">진행중</option>
                            <option value="done">완료</option>
                            <option value="cancelled">취소됨</option>
                        </select>
                        <select wire:model.live="priorityFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">모든 우선순위</option>
                            <option value="high">높음</option>
                            <option value="medium">보통</option>
                            <option value="low">낮음</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 시나리오 카드 목록 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($scenarios as $scenario)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- 헤더 -->
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-800 flex-1 mr-2">{{ $scenario->title }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded priority-{{ $scenario->priority }}">
                                    {{ ucfirst($scenario->priority) }}
                                </span>
                            </div>

                            <!-- 설명 -->
                            @if($scenario->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $scenario->description }}</p>
                            @endif

                            <!-- 진행률 -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>진행률</span>
                                    <span>{{ $scenario->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $scenario->progress }}%"></div>
                                </div>
                            </div>

                            <!-- 상태 및 액션 -->
                            <div class="flex items-center justify-between">
                                <select wire:change="updateStatus({{ $scenario->id }}, $event.target.value)" 
                                        class="text-sm px-2 py-1 border border-gray-300 rounded status-{{ $scenario->status }}">
                                    <option value="todo" {{ $scenario->status === 'todo' ? 'selected' : '' }}>할 일</option>
                                    <option value="in-progress" {{ $scenario->status === 'in-progress' ? 'selected' : '' }}>진행중</option>
                                    <option value="done" {{ $scenario->status === 'done' ? 'selected' : '' }}>완료</option>
                                    <option value="cancelled" {{ $scenario->status === 'cancelled' ? 'selected' : '' }}>취소됨</option>
                                </select>

                                <div class="flex gap-2">
                                    <button wire:click="selectScenario({{ $scenario->id }})" 
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                        상세보기
                                    </button>
                                    <button wire:click="deleteScenario({{ $scenario->id }})" 
                                            onclick="return confirm('정말 삭제하시겠습니까?')"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        삭제
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📋</div>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">시나리오가 없습니다</h3>
                        <p class="text-gray-500 mb-4">첫 번째 개발 시나리오를 생성해보세요</p>
                        <button wire:click="setActiveTab('create')" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            시나리오 생성하기
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- 시나리오 생성 탭 -->
    @if($activeTab === 'create')
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">새 시나리오 생성</h2>
            
            <form wire:submit.prevent="createScenario" class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">제목 *</label>
                    <input type="text" 
                           wire:model="title"
                           id="title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="예: RFx 문서 저장 기능">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">설명</label>
                    <textarea wire:model="description"
                              id="description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="시나리오에 대한 자세한 설명을 입력하세요..."></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">우선순위</label>
                    <select wire:model="priority" 
                            id="priority"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low">낮음</option>
                        <option value="medium">보통</option>
                        <option value="high">높음</option>
                    </select>
                    @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        시나리오 생성
                    </button>
                    <button type="button" 
                            wire:click="setActiveTab('list')"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        취소
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- 시나리오 상세 탭 -->
    @if($activeTab === 'detail' && $selectedScenario)
        <div class="space-y-6">
            <!-- 시나리오 정보 수정 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">시나리오 정보</h2>
                
                <form wire:submit.prevent="updateScenario" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">제목</label>
                            <input type="text" 
                                   wire:model="title"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상태</label>
                            <select wire:model="status" 
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
                        <textarea wire:model="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">우선순위</label>
                        <select wire:model="priority" 
                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="low">낮음</option>
                            <option value="medium">보통</option>
                            <option value="high">높음</option>
                        </select>
                    </div>

                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        업데이트
                    </button>
                </form>
            </div>

            <!-- 요구사항 관리 -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">요구사항</h2>
                
                <!-- 새 요구사항 추가 -->
                <div class="mb-6 p-4 bg-gray-50 rounded-md">
                    <form wire:submit.prevent="addRequirement" class="space-y-3">
                        <div>
                            <input type="text" 
                                   wire:model="requirementContent"
                                   placeholder="새 요구사항을 입력하세요..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('requirementContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                            요구사항 추가
                        </button>
                    </form>
                </div>

                <!-- 요구사항 목록 -->
                <div class="space-y-2">
                    @forelse($selectedScenario->requirements as $requirement)
                        <div class="border border-gray-200 rounded-md p-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" 
                                       wire:click="toggleRequirement({{ $requirement->id }})"
                                       {{ $requirement->completed ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <span class="flex-1 {{ $requirement->completed ? 'requirement-completed' : '' }}">
                                    {{ $requirement->content }}
                                </span>
                                <button wire:click="deleteRequirement({{ $requirement->id }})"
                                        onclick="return confirm('이 요구사항을 삭제하시겠습니까?')"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    삭제
                                </button>
                            </div>

                            <!-- 하위 요구사항 -->
                            @if($requirement->children->count() > 0)
                                <div class="ml-7 mt-2 space-y-2">
                                    @foreach($requirement->children as $child)
                                        <div class="flex items-center gap-3 text-sm">
                                            <input type="checkbox" 
                                                   wire:click="toggleRequirement({{ $child->id }})"
                                                   {{ $child->completed ? 'checked' : '' }}
                                                   class="h-3 w-3 text-blue-600 border-gray-300 rounded">
                                            <span class="flex-1 {{ $child->completed ? 'requirement-completed' : '' }}">
                                                {{ $child->content }}
                                            </span>
                                            <button wire:click="deleteRequirement({{ $child->id }})"
                                                    onclick="return confirm('이 요구사항을 삭제하시겠습니까?')"
                                                    class="text-red-600 hover:text-red-800 text-xs">
                                                삭제
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">📝</div>
                            <p>아직 요구사항이 없습니다</p>
                            <p class="text-sm">위 폼을 사용해서 첫 번째 요구사항을 추가해보세요</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function scenarioManager() {
    return {
        init() {
            console.log('Scenario Manager initialized');
        }
    }
}
</script>