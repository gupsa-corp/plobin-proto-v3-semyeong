{{-- 인증 만료 시 자동 표시되는 로그인 모달 --}}
<div x-data="{ 
    showLogin: false,
    email: '',
    password: '',
    loading: false,
    error: ''
}" 
x-on:show-login-modal.window="showLogin = true"
x-show="showLogin" 
x-cloak
class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96" x-on:click.away="showLogin = false">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">세션이 만료되었습니다</h3>
            <button x-on:click="showLogin = false" class="text-gray-500 hover:text-gray-700">
                ✕
            </button>
        </div>
        
        <p class="text-sm text-gray-600 mb-4">계속하려면 다시 로그인해 주세요.</p>
        
        <form x-on:submit.prevent="
            loading = true;
            error = '';
            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                loading = false;
                if (data.success) {
                    showLogin = false;
                    window.location.reload();
                } else {
                    error = data.message || '로그인에 실패했습니다.';
                }
            })
            .catch(err => {
                loading = false;
                error = '로그인 중 오류가 발생했습니다.';
            })
        ">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
                <input 
                    type="email" 
                    id="email" 
                    x-model="email"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
                <input 
                    type="password" 
                    id="password" 
                    x-model="password"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div x-show="error" class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                <span x-text="error"></span>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button 
                    type="button" 
                    x-on:click="showLogin = false"
                    class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200"
                >
                    취소
                </button>
                <button 
                    type="submit" 
                    :disabled="loading"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                    <span x-show="!loading">로그인</span>
                    <span x-show="loading">로그인 중...</span>
                </button>
            </div>
        </form>
    </div>
</div>