<div class="p-4">
    @if($selectedFunction && $selectedFunctionData)
        <div class="space-y-6">
            {{-- Function Header --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.function-details-header')

            {{-- Dependencies (이 함수가 의존하는 것들) --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.function-dependencies-section')

            {{-- Dependents (이 함수에 의존하는 것들) --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.function-dependents-section')

            {{-- Versions --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.function-versions-section')

            {{-- Tags --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.function-tags-section')
        </div>
    @else
        @include('700-page-sandbox.710-page-function-dependencies.components.function-details-empty')
    @endif
</div>