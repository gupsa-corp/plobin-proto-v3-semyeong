<div>
    <!-- 성공 메시지 -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- 현재 배포 상태 -->
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-700 mb-3">현재 상태</h3>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $this->statusColor }}-100 text-{{ $this->statusColor }}-800">
            <span class="w-2 h-2 bg-{{ $this->statusColor }}-500 rounded-full mr-2"></span>
            {{ $this->statusLabel }} ({{ ucfirst($currentPage->status) }})
        </div>
        <p class="text-sm text-gray-500 mt-2">마지막 업데이트: {{ $currentPage->updated_at->format('Y-m-d H:i:s') }}</p>
    </div>

    <form wire:submit.prevent="updateDeploymentStatus" class="space-y-6">
        <!-- 배포 상태 선택 -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                배포 상태 변경
            </label>
            
            <div class="space-y-3">
                <!-- 초안 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="deploymentStatus"
                        type="radio" 
                        id="status_draft" 
                        value="draft" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="status_draft" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                            <div class="font-medium text-gray-900">초안 (Draft)</div>
                        </div>
                        <div class="text-sm text-gray-500">페이지가 비공개 상태로 저장됩니다.</div>
                    </label>
                </div>

                <!-- 검토 중 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="deploymentStatus"
                        type="radio" 
                        id="status_review" 
                        value="review" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="status_review" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                            <div class="font-medium text-gray-900">검토 중 (Under Review)</div>
                        </div>
                        <div class="text-sm text-gray-500">페이지가 검토 대기 상태입니다.</div>
                    </label>
                </div>

                <!-- 배포됨 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="deploymentStatus"
                        type="radio" 
                        id="status_published" 
                        value="published" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="status_published" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            <div class="font-medium text-gray-900">배포됨 (Published)</div>
                        </div>
                        <div class="text-sm text-gray-500">페이지가 공개되어 접근 가능합니다.</div>
                    </label>
                </div>

                <!-- 아카이브됨 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="deploymentStatus"
                        type="radio" 
                        id="status_archived" 
                        value="archived" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="status_archived" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-gray-600 rounded-full mr-2"></span>
                            <div class="font-medium text-gray-900">아카이브됨 (Archived)</div>
                        </div>
                        <div class="text-sm text-gray-500">페이지가 보관되어 비활성화 상태입니다.</div>
                    </label>
                </div>
            </div>
            @error('deploymentStatus') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- 변경 사유 -->
        <div>
            <label for="change_reason" class="block text-sm font-medium text-gray-700 mb-2">
                변경 사유 (선택사항)
            </label>
            <textarea 
                wire:model="changeReason"
                id="change_reason"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="배포 상태 변경 사유를 입력하세요"
            ></textarea>
            @error('changeReason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- 저장 버튼 -->
        <div class="flex justify-end space-x-3 pt-4">
            <button 
                type="button"
                onclick="window.history.back()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                취소
            </button>
            <button 
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>상태 변경</span>
                <span wire:loading>변경 중...</span>
            </button>
        </div>
    </form>
</div>