<div>
    <!-- 트리거 버튼 (기존 버튼을 대체) -->
    <button wire:click="openModal" class="w-full py-4 bg-teal-500 hover:bg-teal-600 text-white font-bold text-base rounded-lg">
        새로운 조직 생성
    </button>

    <!-- 모달 -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl p-20 w-[614px] h-[468px] flex flex-col justify-center items-start gap-5" wire:click.stop>
                <!-- 모달 헤더 -->
                <div class="flex flex-col items-center gap-3 w-full">
                    <h2 class="text-2xl font-bold text-gray-900 text-center w-full">새로운 조직을 생성합니다</h2>
                </div>

                <!-- 성공 메시지 -->
                @if (session('message'))
                    <div class="w-full bg-green-50 border border-green-200 rounded-lg p-4">
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

                <!-- 폼 -->
                <form wire:submit.prevent="createOrganization" class="flex flex-col items-center gap-5 w-full">
                    <div class="flex flex-col items-center gap-4 w-full">
                        <div class="flex flex-col items-start gap-2 w-full">
                            <!-- 조직 이름 입력 -->
                            <div class="flex flex-col justify-center items-start gap-1 w-full">
                                <div class="flex items-center pl-1 gap-2">
                                    <label for="orgName" class="text-sm text-gray-900">조직 이름</label>
                                </div>
                                <input type="text" 
                                       id="orgName" 
                                       wire:model="name"
                                       placeholder="국영문 대소문자 1~25자"
                                       class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 조직 설명 입력 (선택사항) -->
                            <div class="flex flex-col justify-center items-start gap-1 w-full mt-2">
                                <div class="flex items-center pl-1 gap-2">
                                    <label for="orgDescription" class="text-sm text-gray-900">조직 설명 (선택사항)</label>
                                </div>
                                <textarea id="orgDescription" 
                                          wire:model="description"
                                          rows="3"
                                          placeholder="조직에 대한 간단한 설명을 입력하세요"
                                          class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"></textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- 생성 버튼 -->
                        <button type="submit"
                                class="w-full py-4 bg-teal-500 hover:bg-teal-600 text-white font-bold text-base rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!name.trim()">
                            생성하기
                        </button>
                    </div>
                </form>

                <!-- 닫기 버튼 -->
                <button type="button" 
                        wire:click="closeModal"
                        class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>