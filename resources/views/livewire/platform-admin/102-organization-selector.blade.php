{{-- 조직 선택기 Livewire 컴포넌트 --}}
<div class="mb-6 bg-gray-50 p-4 rounded-lg border">
    <h4 class="text-sm font-medium text-gray-700 mb-3">권한 범위 선택</h4>
    
    {{-- 스코프 선택 라디오 버튼 --}}
    <div class="space-y-3">
        <div class="flex items-center">
            <input wire:model.live="selectedScope" 
                   id="scope-platform" 
                   type="radio" 
                   value="platform" 
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
            <label for="scope-platform" class="ml-2 block text-sm text-gray-700">
                <span class="font-medium">플랫폼 권한</span>
                <span class="text-gray-500 ml-1">(시스템 전체 권한)</span>
            </label>
        </div>
        
        <div class="flex items-center">
            <input wire:model.live="selectedScope" 
                   id="scope-organization" 
                   type="radio" 
                   value="organization" 
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
            <label for="scope-organization" class="ml-2 block text-sm text-gray-700">
                <span class="font-medium">조직 권한</span>
                <span class="text-gray-500 ml-1">(특정 조직 권한)</span>
            </label>
        </div>
    </div>
    
    {{-- 조직 선택 드롭다운 (조직 스코프 선택시만 표시) --}}
    @if($selectedScope === 'organization')
        <div class="mt-4 pl-6">
            <label for="organization-select" class="block text-sm font-medium text-gray-700 mb-2">
                조직 선택
            </label>
            <select wire:model.live="selectedOrganization" 
                    id="organization-select"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">조직을 선택하세요</option>
                @foreach($organizations as $org)
                    <option value="{{ $org['id'] }}">{{ $org['name'] }}</option>
                @endforeach
            </select>
        </div>
    @endif
    
    {{-- 현재 선택상태 표시 --}}
    <div class="mt-4 text-xs text-gray-500">
        현재 선택: 
        @if($selectedScope === 'platform')
            <span class="font-medium text-blue-600">플랫폼 권한</span>
        @elseif($selectedScope === 'organization' && $selectedOrganization)
            <span class="font-medium text-green-600">
                조직 권한 - {{ collect($organizations)->firstWhere('id', $selectedOrganization)['name'] ?? '선택됨' }}
            </span>
        @elseif($selectedScope === 'organization')
            <span class="font-medium text-orange-600">조직 선택 필요</span>
        @endif
    </div>
</div>