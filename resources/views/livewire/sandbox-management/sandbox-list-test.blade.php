<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">{{ $testMessage }}</h2>
    <p class="text-gray-600">이 메시지가 보이면 Livewire가 올바르게 작동하고 있습니다.</p>
    
    <div class="mt-4">
        <button wire:click="$refresh" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            새로고침 테스트
        </button>
    </div>
</div>