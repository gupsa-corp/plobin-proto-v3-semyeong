<main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-primary-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3a1 1 0 011-1h2.586l6.414-6.414A6 6 0 1721 9z"></path>
                </svg>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                비밀번호 찾기
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                등록된 이메일 주소를 입력하시면<br>비밀번호 재설정 링크를 보내드립니다
            </p>
        </div>

        @if (session('status'))
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
                        <p class="mt-1 text-sm text-green-700">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" wire:submit.prevent="sendResetLink">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        이메일 주소
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        wire:model="email"
                        autocomplete="email" 
                        class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                        placeholder="example@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out"
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
    </div>
</main>