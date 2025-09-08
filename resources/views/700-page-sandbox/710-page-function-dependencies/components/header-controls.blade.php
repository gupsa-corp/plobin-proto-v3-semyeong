<div class="bg-white border-b border-gray-200 p-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            {{-- Search Input --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.search-input')

            {{-- Category Filter --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.category-filter')
        </div>

        {{-- View Mode Toggle --}}
        @include('700-page-sandbox.710-page-function-dependencies.components.view-mode-toggle')
    </div>
</div>