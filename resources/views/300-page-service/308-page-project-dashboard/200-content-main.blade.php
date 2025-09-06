<!-- 페이지별 커스텀 콘텐츠 -->
<div class="px-6 py-6" x-data="projectTabs()">

    <!-- 탭 네비게이션 -->
    <div class="mb-6">
        <div class="border-b border-gray-200 flex justify-between items-center">
            <nav class="-mb-px flex space-x-8 items-center" aria-label="Tabs">
                
                <!-- 하위 페이지 추가 버튼 -->
                <button @click="showCreatePageModal = true"
                        class="flex items-center space-x-1 px-3 py-1 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md border border-blue-300">
                    <i class="fas fa-plus text-xs"></i>
                    <span>하위 페이지 추가</span>
                </button>
            </nav>
        </div>
        
        <!-- 하위 페이지 생성 모달 -->
        <div x-show="showCreatePageModal" 
             x-cloak 
             class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"
             @click.away="showCreatePageModal = false">
            <div class="bg-white rounded-lg shadow-xl w-96 p-6" @click.stop>
                <h3 class="text-lg font-medium text-gray-900 mb-4">새 하위 페이지 만들기</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">페이지 이름</label>
                        <input type="text" 
                               x-model="newPage.name" 
                               placeholder="예: 회의록, 기획서 등"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">부모 페이지</label>
                        <select x-model="newPage.parentId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">최상위 페이지</option>
                            <template x-for="page in availablePages" :key="page.id">
                                <option :value="page.id" x-text="`${'　'.repeat(page.level * 2)}${page.title}`"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">페이지 타입</label>
                        <select x-model="newPage.type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="document">문서</option>
                            <option value="iframe">외부 링크</option>
                            <option value="board">게시판</option>
                            <option value="kanban">칸반 보드</option>
                        </select>
                    </div>
                    
                    <div x-show="newPage.type === 'iframe'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                        <input type="url" 
                               x-model="newPage.url" 
                               placeholder="https://example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">설명 (선택사항)</label>
                        <textarea x-model="newPage.description" 
                                  rows="3" 
                                  placeholder="페이지에 대한 간단한 설명"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="showCreatePageModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        취소
                    </button>
                    <button @click="createSubPage()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        페이지 만들기
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 메인 콘텐츠 영역 -->
    <div x-data="pageContent()">
        @if(isset($currentPageId) && $currentPageId)
            <!-- 동적 페이지 콘텐츠 -->
            <div x-show="!loading">
                <!-- 페이지 헤더 -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i :class="pageData.icon || 'fas fa-file'" class="text-gray-600"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl font-semibold text-gray-900" x-text="pageData.title || '페이지'"></h1>
                                    <p class="text-sm text-gray-500" x-text="pageData.description || ''"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full"
                                      :class="pageData.status === 'published' ? 'bg-green-100 text-green-800' : 
                                              pageData.status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                                              'bg-gray-100 text-gray-800'"
                                      x-text="pageData.status === 'published' ? '공개' : 
                                              pageData.status === 'draft' ? '임시' : '보관'">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 페이지 타입별 콘텐츠 -->
                <div class="bg-white rounded-lg shadow">
                    <!-- iframe 타입 -->
                    <div x-show="pageData.type === 'iframe'">
                        <div x-show="pageData.config && pageData.config.url" 
                             class="rounded-lg overflow-hidden" 
                             style="height: 600px;">
                            <iframe :src="pageData.config && pageData.config.url" 
                                    class="w-full h-full border-0"
                                    sandbox="allow-scripts allow-same-origin allow-forms allow-top-navigation allow-popups">
                            </iframe>
                        </div>
                        <div x-show="!pageData.config || !pageData.config.url" 
                             class="p-8 text-center text-gray-500">
                            <i class="fas fa-external-link-alt text-4xl mb-4 text-gray-300"></i>
                            <p>외부 링크 URL이 설정되지 않았습니다.</p>
                        </div>
                    </div>

                    <!-- 문서 타입 -->
                    <div x-show="pageData.type === 'document'" class="p-6">
                        <div class="prose max-w-none">
                            <div class="border-2 border-dashed border-gray-200 rounded-lg p-8 text-center">
                                <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">문서 편집기</h3>
                                <p class="text-gray-500 mb-4">여기에 문서 내용을 작성할 수 있습니다.</p>
                                <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                    편집 시작하기
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 게시판 타입 -->
                    <div x-show="pageData.type === 'board'" class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">게시판</h3>
                            <p class="text-gray-500 mb-4">게시판 기능을 설정할 수 있습니다.</p>
                            <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                게시판 설정하기
                            </button>
                        </div>
                    </div>

                    <!-- 칸반 보드 타입 -->
                    <div x-show="pageData.type === 'kanban'" class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-columns text-4xl mb-4 text-gray-300"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">칸반 보드</h3>
                            <p class="text-gray-500 mb-4">작업을 칸반 보드로 관리할 수 있습니다.</p>
                            <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                칸반 보드 설정하기
                            </button>
                        </div>
                    </div>

                    <!-- 기본/알 수 없는 타입 -->
                    <div x-show="!pageData.type || !['iframe', 'document', 'board', 'kanban'].includes(pageData.type)" class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-question-circle text-4xl mb-4 text-gray-300"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">페이지 콘텐츠</h3>
                            <p class="text-gray-500">페이지 타입을 확인할 수 없습니다.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 로딩 상태 -->
            <div x-show="loading" class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-12">
                    <div class="mx-auto w-12 h-12 border-4 border-blue-200 border-top-blue-600 rounded-full animate-spin mb-4"></div>
                    <p class="text-gray-500">페이지를 불러오는 중...</p>
                </div>
            </div>
        @else
            <!-- 페이지 없음 상태 -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L19.7071 9.70711C19.8946 9.89464 20 10.149 20 10.4142V19C20 20.1046 19.1046 21 18 21H17Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">페이지가 없습니다</h3>
                    <p class="text-gray-500 mb-4">+ 버튼을 눌러 첫 페이지를 만들어보세요</p>
                </div>
            </div>
        @endif
    </div>


</div>
