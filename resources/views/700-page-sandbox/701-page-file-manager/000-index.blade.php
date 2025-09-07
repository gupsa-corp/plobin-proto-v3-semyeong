@extends('700-page-sandbox.700-common.700-common-sandbox')

@section('title', '파일 관리')

@section('content')
<div class="space-y-6" x-data="{
    currentPath: 'files/views',
    content: '',
    fileName: '',
    list: { dirs: [], files: [] }
}">
    <!-- 알림 메시지 -->
    <div id="message-area">
        <!-- 구현필요 -->
    </div>

    <!-- 디렉토리 네비게이션 -->
    <div class="bg-gray-50 p-4 rounded">
        <h3 class="font-medium text-gray-900 mb-3">디렉토리 선택</h3>
        <div class="grid grid-cols-3 gap-2">
            <button @click="selectDirectory('files/views')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50"
                    :class="currentPath === 'files/views' ? 'ring-2 ring-blue-500' : ''">
                Views
            </button>
            <button @click="selectDirectory('files/controllers')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50"
                    :class="currentPath === 'files/controllers' ? 'ring-2 ring-blue-500' : ''">
                Controllers
            </button>
            <button @click="selectDirectory('files/models')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50"
                    :class="currentPath === 'files/models' ? 'ring-2 ring-blue-500' : ''">
                Models
            </button>
            <button @click="selectDirectory('files/livewire')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50"
                    :class="currentPath === 'files/livewire' ? 'ring-2 ring-blue-500' : ''">
                Livewire
            </button>
            <button @click="selectDirectory('files/routes')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50"
                    :class="currentPath === 'files/routes' ? 'ring-2 ring-blue-500' : ''">
                Routes
            </button>
            <button @click="selectDirectory('files/migrations')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50"
                    :class="currentPath === 'files/migrations' ? 'ring-2 ring-blue-500' : ''">
                Migrations
            </button>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- 파일 목록 -->
        <div class="bg-gray-50 p-4 rounded">
            <h3 class="font-medium text-gray-900 mb-3" x-text="currentPath"></h3>

            <!-- 하위 디렉토리 -->
            <template x-if="list.dirs && list.dirs.length > 0">
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">디렉토리</h4>
                    <template x-for="dir in list.dirs" :key="dir">
                        <button @click="selectDirectory(dir)"
                                class="block w-full text-left px-2 py-1 text-sm text-blue-600 hover:bg-white rounded">
                            📁 <span x-text="dir.split('/').pop()"></span>
                        </button>
                    </template>
                </div>
            </template>

            <!-- 파일 목록 -->
            <template x-if="list.files && list.files.length > 0">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">파일</h4>
                    <template x-for="file in list.files" :key="file">
                        <div class="flex items-center justify-between py-1">
                            <button @click="selectFile(file)"
                                    class="text-left text-sm text-gray-900 hover:text-blue-600">
                                📄 <span x-text="file.split('/').pop()"></span>
                            </button>
                            <button @click="deleteFile(file)"
                                    class="text-red-500 hover:text-red-700 text-xs">
                                삭제
                            </button>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="(!list.files || list.files.length === 0) && (!list.dirs || list.dirs.length === 0)">
                <p class="text-gray-500 text-sm">파일이 없습니다.</p>
            </template>
        </div>

        <!-- 파일 편집기 -->
        <div class="col-span-2">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">파일명</label>
                <input x-model="fileName"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="파일명을 입력하세요">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">내용</label>
                <textarea x-model="content"
                          rows="20"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                          placeholder="파일 내용을 입력하세요"></textarea>
            </div>

            <div class="flex space-x-3">
                <button @click="saveFile"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    저장
                </button>
                <button @click="refreshList"
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    새로고침
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function selectDirectory(dir) {
    // 구현필요
}

function selectFile(file) {
    // 구현필요
}

function saveFile() {
    // 구현필요
}

function deleteFile(file) {
    // 구현필요
}

function refreshList() {
    // 구현필요
}
</script>
@endsection
