<main class="flex-1 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">회원가입</h2>
        
        <form id="signupForm">
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">성</label>
                    <input type="text" id="first_name" name="first_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div id="firstNameError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">이름</label>
                    <input type="text" id="last_name" name="last_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div id="lastNameError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
            </div>

            <div class="mb-4">
                <label for="nickname" class="block text-sm font-medium text-gray-700 mb-1">닉네임</label>
                <input type="text" id="nickname" name="nickname"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div id="nicknameError" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">휴대폰 번호</label>
                <div class="grid grid-cols-3 gap-2">
                    <select id="country_code" name="country_code"
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="+82">+82 (한국)</option>
                        <!-- 동적으로 로드됨 -->
                    </select>
                    <input type="tel" id="phone_number" name="phone_number" placeholder="01012345678"
                        class="col-span-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div id="phoneError" class="text-red-500 text-sm mt-1 hidden"></div>
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
