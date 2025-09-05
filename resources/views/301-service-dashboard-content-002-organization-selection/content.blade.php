{{-- 조직 선택 화면 --}}
<div id="organizationSelectionScreen" style="display: none;">
    {{-- 조직선택 헤더 --}}
    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">조직선택</h1>
            <p class="text-lg text-gray-500">조직을 선택해주세요</p>
        </div>
        <button id="createOrganizationBtn" class="flex items-center justify-center gap-1 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white font-bold text-sm rounded-full h-10">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M15.625 9.375H10.625V4.375C10.625 4.02982 10.3452 3.75 10 3.75C9.65482 3.75 9.375 4.02982 9.375 4.375V9.375H4.375C4.02982 9.375 3.75 9.65482 3.75 10C3.75 10.3452 4.02982 10.625 4.375 10.625H9.375V15.625C9.375 15.9702 9.65482 16.25 10 16.25C10.3452 16.25 10.625 15.9702 10.625 15.625V10.625H15.625C15.9702 10.625 16.25 10.3452 16.25 10C16.25 9.65482 15.9702 9.375 15.625 9.375Z" fill="white"/>
            </svg>
            새조직 생성하기
        </button>
    </div>

    {{-- 조직 리스트 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="organizationList">
        {{-- 조직 블록들이 여기에 동적으로 로드됩니다 --}}
    </div>
</div>
