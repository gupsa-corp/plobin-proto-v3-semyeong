<div class="p-4">
    {{-- Header --}}
    @include('700-page-sandbox.710-page-function-dependencies.components.function-list-header')
    
    {{-- Function Items --}}
    <div class="space-y-2">
        @forelse($functions as $functionName => $functionData)
            @include('700-page-sandbox.710-page-function-dependencies.components.function-list-item', [
                'functionName' => $functionName,
                'functionData' => $functionData
            ])
        @empty
            @include('700-page-sandbox.710-page-function-dependencies.components.function-list-empty')
        @endforelse
    </div>
</div>