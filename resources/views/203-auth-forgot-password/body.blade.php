<main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-primary-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                비밀번호 찾기
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                등록된 이메일 주소를 입력하시면<br>비밀번호 재설정 링크를 보내드립니다
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="/api/auth/forgot-password" method="POST" id="forgotPasswordForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        이메일 주소
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                        placeholder="example@email.com"
                    >
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out"
                    id="submitButton"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-primary-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </span>
                    비밀번호 재설정 링크 전송
                </button>
            </div>

            <div class="text-center space-y-4">
                <div>
                    <a href="/login" class="font-medium text-primary-600 hover:text-primary-500 text-sm">
                        ← 로그인으로 돌아가기
                    </a>
                </div>
                <div>
                    <span class="text-sm text-gray-600">
                        아직 계정이 없으신가요? 
                        <a href="/signup" class="font-medium text-primary-600 hover:text-primary-500">
                            회원가입
                        </a>
                    </span>
                </div>
            </div>
        </form>

        <!-- 에러 메시지 표시 영역 -->
        <div id="errorMessage" class="mt-4 hidden">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800" id="errorTitle">
                            오류 발생
                        </h3>
                        <p class="mt-1 text-sm text-red-700" id="errorText">
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 성공 메시지 표시 영역 -->
        <div id="successMessage" class="mt-4 hidden">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            이메일 전송 완료
                        </h3>
                        <p class="mt-1 text-sm text-green-700" id="successText">
                            입력하신 이메일로 비밀번호 재설정 링크를 전송했습니다.<br>
                            이메일을 확인해 주세요.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 로딩 상태 표시 영역 -->
        <div id="loadingMessage" class="mt-4 hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="animate-spin h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            처리 중입니다...
                        </h3>
                        <p class="mt-1 text-sm text-blue-700">
                            이메일을 전송하고 있습니다. 잠시만 기다려 주세요.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- AJAX 스크립트 포함 --}}
@include('203-auth-forgot-password.ajax')