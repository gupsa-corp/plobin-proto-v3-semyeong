<!-- 설정 탭 -->
<div class="space-y-6">
    <!-- 새 페이지 추가 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">새 페이지 추가</h3>
        
        <form class="space-y-4" @submit.prevent="addPageFromSettings()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">페이지 이름</label>
                    <input type="text" 
                           x-model="settingsPage.name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           placeholder="페이지 이름을 입력하세요"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">페이지 타입</label>
                    <select x-model="settingsPage.type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="iframe">iframe 구성</option>
                        <option value="text-editor">텍스트 에디터</option>
                        <option value="gantt-chart">간트차트 템플릿</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">아이콘</label>
                <select x-model="settingsPage.icon" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="fas fa-globe">🌐 웹페이지 (iframe)</option>
                    <option value="fas fa-edit">✏️ 텍스트 에디터</option>
                    <option value="fas fa-chart-gantt">📊 간트차트</option>
                    <option value="fas fa-file">📄 일반 파일</option>
                    <option value="fas fa-star">⭐ 중요</option>
                    <option value="fas fa-chart-bar">📈 차트</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">설명</label>
                <textarea x-model="settingsPage.description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                          placeholder="페이지 설명을 입력하세요"></textarea>
            </div>
            
            <!-- 타입별 추가 설정 -->
            <div x-show="settingsPage.type === 'iframe'">
                <label class="block text-sm font-medium text-gray-700 mb-2">iframe URL</label>
                <input type="url" 
                       x-model="settingsPage.iframeUrl" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                       placeholder="https://example.com">
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    페이지 추가
                </button>
            </div>
        </form>
    </div>

    <!-- 기존 페이지 관리 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">페이지 관리</h3>
        
        <div class="space-y-3">
            <template x-for="page in pages" :key="page.id">
                <div class="flex items-center justify-between p-3 border rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i :class="page.icon" class="text-gray-600"></i>
                        <div>
                            <div class="font-medium" x-text="page.name"></div>
                            <div class="text-sm text-gray-500" x-text="page.description"></div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="editPage(page)" 
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            편집
                        </button>
                        <button @click="deletePage(page.id)" 
                                class="text-red-600 hover:text-red-800 text-sm">
                            삭제
                        </button>
                    </div>
                </div>
            </template>
            
            <div x-show="pages.length === 0" class="text-center text-gray-500 py-8">
                추가된 페이지가 없습니다.
            </div>
        </div>
    </div>

    <!-- 프로젝트 설정 -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">프로젝트 설정</h3>
        <div class="text-gray-500">
            구현필요
        </div>
    </div>
</div>