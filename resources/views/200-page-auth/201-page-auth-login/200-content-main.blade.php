<main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-primary-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                로그인
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                계정에 로그인하여 Plobin을 이용하세요
            </p>
        </div>
        
        <div class="mt-8">
            <livewire:auth.login />
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="text-sm">
                <a href="/forgot-password" class="font-medium text-primary-600 hover:text-primary-500">
                    비밀번호를 잊으셨나요?
                </a>
            </div>
        </div>

        <div class="text-center">
            <span class="text-sm text-gray-600">
                아직 계정이 없으신가요? 
                <a href="/signup" class="font-medium text-primary-600 hover:text-primary-500">
                    회원가입
                </a>
            </span>
        </div>
    </div>
</main>
