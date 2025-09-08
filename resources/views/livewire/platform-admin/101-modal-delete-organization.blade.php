{{--
===========================================
개발 가이드라인 (DEVELOPMENT GUIDELINES)
===========================================

⚠️ 중요: 이 프로젝트에서는 순수 JavaScript 사용을 금지합니다
❌ 사용 금지: Vanilla JS, jQuery, Alpine.js의 복잡한 로직
✅ 사용 필수: Livewire + Filament 조합만 사용

모든 상호작용과 동적 기능은 다음으로만 구현:
- Livewire: 서버사이드 상태관리, 이벤트 처리
- Filament: UI 컴포넌트, 폼, 테이블 등
- 간단한 Alpine.js: 토글, 드롭다운 등 최소한의 UI 상호작용만

JavaScript가 필요한 경우 → Livewire로 재작성 필수
복잡한 UI가 필요한 경우 → Filament 컴포넌트 사용

===========================================
--}}

{{-- 삭제 확인 모달 --}}
@if($confirmingDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-5">조직 삭제</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        정말로 이 조직을 삭제하시겠습니까?<br>
                        이 작업은 되돌릴 수 없습니다.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-5">
                    <button 
                        wire:click="cancelDelete"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        취소
                    </button>
                    <button 
                        wire:click="deleteOrganization"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        삭제
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif