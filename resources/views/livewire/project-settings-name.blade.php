<!-- 프로젝트 이름 변경 폼 -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">프로젝트 이름 변경</h3>
        <p class="mt-1 text-sm text-gray-500">
            프로젝트의 이름을 변경할 수 있습니다. 이 변경은 모든 팀원에게 표시됩니다.
        </p>

        <form wire:submit.prevent="updateName">
            <div class="mt-6">
                <label for="project-name" class="block text-sm font-medium text-gray-700">새 프로젝트 이름</label>
                <div class="mt-1">
                    <input type="text"
                           wire:model.defer="projectName"
                           id="project-name"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('projectName') border-red-300 @enderror"
                           placeholder="프로젝트 이름을 입력하세요">
                </div>
                @error('projectName')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button"
                        wire:click="cancel"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    취소
                </button>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span wire:loading.remove>변경 저장</span>
                    <span wire:loading>저장 중...</span>
                </button>
            </div>
        </form>
    </div>
</div>