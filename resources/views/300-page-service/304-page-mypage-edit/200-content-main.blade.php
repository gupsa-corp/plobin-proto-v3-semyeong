<div class="p-6" x-data="profileEditPage()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">개인정보 수정</h1>
        <p class="text-gray-600 mt-1">계정의 개인정보를 수정할 수 있습니다.</p>
        <div class="mt-2">
            <a href="/mypage" class="text-blue-600 hover:text-blue-700 text-sm">← 프로필로 돌아가기</a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- 비밀번호 변경 -->
        <div class="bg-white shadow rounded-lg">

            @livewire('profile.change-password')

        <!-- 기본정보 수정 -->
        <div class="bg-white shadow rounded-lg">
            @livewire('profile.edit-profile')
    </div>
</div>
