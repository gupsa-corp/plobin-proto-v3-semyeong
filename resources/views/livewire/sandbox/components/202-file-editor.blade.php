<!-- 편집 영역 -->
<div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">파일명</label>
        <input wire:model.live="fileName"
               value="{{ $fileName }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="파일명을 입력하세요">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">내용</label>
        <textarea wire:model="content"
                  rows="20"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                  placeholder="파일 내용을 입력하세요">{{ $content }}</textarea>
    </div>

    <div class="flex space-x-3">
        <button wire:click="saveFile"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            저장
        </button>
        <button wire:click="refreshList"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
            새로고침
        </button>
    </div>
</div>