<div class="space-y-6">
    {{-- 헤더 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📋 Form Publisher - 폼 목록</h1>
                <p class="text-gray-600 mt-1">샌드박스 폼 생성 및 관리 도구</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('sandbox.form-publisher.editor') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    ✏️ 새 폼 만들기
                </a>
                <a href="{{ route('sandbox.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-md transition-colors duration-200">
                    🏠 홈
                </a>
            </div>
        </div>
    </div>

    {{-- 통계 카드 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-md">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $forms->total() }}</h3>
                    <p class="text-gray-600">총 폼 개수</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-md">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $forms->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                    <p class="text-gray-600">최근 7일 생성</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-md">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    @if($forms->isNotEmpty())
                        <h3 class="text-lg font-semibold text-gray-900">{{ $forms->first()->title }}</h3>
                        <p class="text-gray-600">최근 생성 폼</p>
                    @else
                        <h3 class="text-lg font-semibold text-gray-900">-</h3>
                        <p class="text-gray-600">폼이 없습니다</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 검색 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex space-x-4">
            <div class="flex-1">
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="폼 제목이나 설명으로 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                🔍 검색
            </button>
        </div>
    </div>

    {{-- 폼 목록 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">폼 목록 (총 {{ $forms->total() }}개)</h2>
                <a href="{{ route('sandbox.form-publisher.editor') }}" 
                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    ➕ 새 폼 만들기
                </a>
            </div>
        </div>

        @if($forms->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">제목</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">설명</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">생성일</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">수정일</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $form->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $form->title }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 max-w-xs truncate">{{ $form->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $form->created_at->format('m/d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $form->updated_at->format('m/d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('sandbox.form-publisher.preview', $form->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200" title="미리보기">
                                            👀
                                        </a>
                                        <a href="{{ route('sandbox.form-publisher.editor', ['edit' => $form->id]) }}" 
                                           class="text-green-600 hover:text-green-800 transition-colors duration-200" title="편집">
                                            ✏️
                                        </a>
                                        <button wire:click="duplicateForm({{ $form->id }})" 
                                                class="text-yellow-600 hover:text-yellow-800 transition-colors duration-200" title="복사">
                                            📋
                                        </button>
                                        <button wire:click="deleteForm({{ $form->id }})" 
                                                onclick="return confirm('정말 삭제하시겠습니까?')"
                                                class="text-red-600 hover:text-red-800 transition-colors duration-200" title="삭제">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 페이지네이션 --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $forms->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">폼이 없습니다</h3>
                <p class="mt-1 text-gray-500">첫 번째 폼을 만들어보세요.</p>
                <div class="mt-6">
                    <a href="{{ route('sandbox.form-publisher.editor') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        ➕ 새 폼 만들기
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>