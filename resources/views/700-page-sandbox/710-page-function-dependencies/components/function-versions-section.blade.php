<div>
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-lg font-medium text-gray-900">버전 정보</h4>
        <span class="text-sm text-gray-500 bg-purple-100 px-2 py-1 rounded">
            {{ count($selectedFunctionData['info']['versions'] ?? []) }}개
        </span>
    </div>
    
    @if(!empty($selectedFunctionData['info']['versions']))
        <div class="flex flex-wrap gap-2">
            @foreach($selectedFunctionData['info']['versions'] as $version)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $version === 'release' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                    @if($version === 'release')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    {{ $version }}
                </span>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-sm">버전 정보가 없습니다</p>
        </div>
    @endif
</div>