<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">기본 정보 수정</h3>
    </div>
    <div class="p-6">
        @if (session('message'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="updateProfile" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">이름 <span class="text-red-500">*</span></label>
                <input type="text"
                       wire:model="name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       id="name"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">이메일</label>
                <input type="email"
                       wire:model="email"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       id="email"
                       readonly>
                <p class="text-xs text-gray-500 mt-1">이메일은 변경할 수 없습니다.</p>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">연락처</label>
                <input type="tel"
                       wire:model="phone"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       id="phone"
                       placeholder="010-0000-0000">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">소속</label>
                <input type="text"
                       wire:model="organization"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       id="organization"
                       readonly>
                <p class="text-xs text-gray-500 mt-1">소속은 조직 목록에서 변경할 수 있습니다.</p>
            </div>

            <div class="pt-4 flex space-x-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">저장</button>
                <a href="/mypage" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">취소</a>
            </div>
        </form>
    </div>
</div>
