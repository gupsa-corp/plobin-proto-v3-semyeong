<!-- 디렉토리 네비게이션 -->
<div class="bg-gray-50 p-4 rounded">
    <h3 class="font-medium text-gray-900 mb-3">디렉토리 선택</h3>
    <div class="grid grid-cols-3 gap-2">
        <button wire:click="selectDirectory('files/views')"
                class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/views' ? 'ring-2 ring-blue-500' : '' }}">
            Views
        </button>
        <button wire:click="selectDirectory('files/controllers')"
                class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/controllers' ? 'ring-2 ring-blue-500' : '' }}">
            Controllers
        </button>
        <button wire:click="selectDirectory('files/models')"
                class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/models' ? 'ring-2 ring-blue-500' : '' }}">
            Models
        </button>
        <button wire:click="selectDirectory('files/livewire')"
                class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/livewire' ? 'ring-2 ring-blue-500' : '' }}">
            Livewire
        </button>
        <button wire:click="selectDirectory('files/routes')"
                class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/routes' ? 'ring-2 ring-blue-500' : '' }}">
            Routes
        </button>
        <button wire:click="selectDirectory('files/migrations')"
                class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/migrations' ? 'ring-2 ring-blue-500' : '' }}">
            Migrations
        </button>
    </div>
</div>