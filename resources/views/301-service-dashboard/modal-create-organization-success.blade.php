{{-- 조직 생성 완료 모달 --}}
<div id="createOrganizationSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-20 w-[614px] h-[433px] flex flex-col justify-center items-start gap-5">
        {{-- 모달 헤더 --}}
        <div class="flex flex-col items-center gap-3 w-full h-[197px]">
            <h2 id="successTitle" class="text-2xl font-bold text-gray-900 text-center w-full h-9 flex items-center justify-center">
                조직이 생성되었습니다
            </h2>
            
            {{-- 축하 일러스트 영역 --}}
            <div class="relative w-full h-[149px] flex items-center justify-center">
                {{-- 축하 아이콘들 --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    {{-- 색종이 조각들 --}}
                    <div class="absolute w-2 h-2 bg-red-400 rounded-sm animate-bounce" style="top: 20px; left: 100px; animation-delay: 0.1s;"></div>
                    <div class="absolute w-2 h-2 bg-yellow-400 rounded-sm animate-bounce" style="top: 30px; right: 80px; animation-delay: 0.3s;"></div>
                    <div class="absolute w-3 h-3 bg-blue-400 rounded-sm animate-bounce" style="top: 50px; left: 50px; animation-delay: 0.2s;"></div>
                    <div class="absolute w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="top: 40px; right: 120px; animation-delay: 0.4s;"></div>
                    <div class="absolute w-3 h-3 bg-yellow-400 rounded-full animate-bounce" style="bottom: 40px; left: 80px; animation-delay: 0.6s;"></div>
                    <div class="absolute w-2 h-2 bg-red-400 rounded-full animate-bounce" style="bottom: 30px; right: 60px; animation-delay: 0.5s;"></div>
                    
                    {{-- 축하 텍스트 --}}
                    <div class="text-6xl">🎉</div>
                </div>
                
                {{-- URL 표시 박스 --}}
                <div class="absolute bottom-0 w-[356px] h-14 bg-teal-50 border-2 border-teal-500 rounded flex flex-col justify-center items-center px-5 gap-2">
                    <p class="text-sm text-gray-700">이제 다음의 경로로 조직에 접근할 수 있어요</p>
                    <p id="organizationUrl" class="text-base font-bold text-teal-600">www.plobin.com/orgs/example</p>
                </div>
            </div>
        </div>

        {{-- 확인 버튼 --}}
        <div class="flex flex-col items-center gap-5 w-full h-14">
            <button type="button" id="successConfirmBtn" 
                    class="w-full py-4 bg-teal-500 hover:bg-teal-600 text-white font-bold text-base rounded-lg">
                확인
            </button>
        </div>

        {{-- 닫기 버튼 --}}
        <button type="button" id="closeSuccessModalBtn" 
                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
</div>