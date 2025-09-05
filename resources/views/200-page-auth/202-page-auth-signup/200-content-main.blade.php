<main class="flex-1 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">회원가입</h2>
        
        <form id="signupForm">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">이름</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div id="nameError" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
                <div class="relative">
                    <input type="email" id="email" name="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" id="checkEmailBtn"
                        class="absolute right-2 top-2 px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition duration-200">
                        중복확인
                    </button>
                </div>
                <div id="emailStatus" class="text-sm mt-1 hidden"></div>
                <div id="emailError" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div id="passwordError" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">비밀번호 확인</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div id="passwordConfirmationError" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                회원가입
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">이미 계정이 있으신가요? 
                <a href="/login" class="text-blue-500 hover:text-blue-600">로그인</a>
            </p>
        </div>
    </div>


    {{-- AJAX 로직 포함 --}}
    @include('200-page-auth.202-page-auth-signup.500-ajax-signup')
    
    {{-- JavaScript 로직 포함 --}}
    @include('200-page-auth.202-page-auth-signup.400-js-signup')
</main>
