{{-- 조직 생성 요청 모달 --}}
<div id="createOrganizationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-20 w-[614px] h-[468px] flex flex-col justify-center items-start gap-5">
        {{-- 모달 헤더 --}}
        <div class="flex flex-col items-center gap-3 w-full">
            <h2 class="text-2xl font-bold text-gray-900 text-center w-full">새로운 조직을 생성합니다</h2>
        </div>

        {{-- 폼 --}}
        <div class="flex flex-col items-center gap-5 w-full">
            <div class="flex flex-col items-center gap-4 w-full">
                <div class="flex flex-col items-start gap-2 w-full">
                    {{-- 조직 이름 입력 --}}
                    <div class="flex flex-col justify-center items-start gap-1 w-full">
                        <div class="flex items-center pl-1 gap-2">
                            <label for="orgName" class="text-sm text-gray-900">조직 이름</label>
                        </div>
                        <input type="text" id="orgName" placeholder="국영문 대소문자 1~25자" 
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    {{-- 하위 도메인 입력 --}}
                    <div class="flex flex-col justify-center items-start gap-1 w-full">
                        <div class="flex items-center pl-1 gap-2">
                            <label for="subdomain" class="text-sm text-gray-900">하위 도메인</label>
                        </div>
                        <div class="flex items-center gap-2 w-full">
                            <input type="text" id="subdomain" placeholder="영문 소문자 3~12자" 
                                   class="flex-1 px-3 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <button type="button" id="checkDuplicateBtn" 
                                    class="px-6 py-3 border border-teal-500 bg-teal-50 text-teal-500 font-bold text-sm rounded-lg hover:bg-teal-100">
                                중복확인
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">e.g. acme → www.plobin.com/orgs/acme</p>
                    </div>
                </div>

                {{-- 생성 버튼 --}}
                <button type="button" id="createOrgSubmitBtn" 
                        class="w-full py-4 bg-teal-500 hover:bg-teal-600 text-white font-bold text-base rounded-lg">
                    생성하기
                </button>
            </div>
        </div>

        {{-- 닫기 버튼 --}}
        <button type="button" id="closeModalBtn" 
                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
</div>