<div class="space-y-6">
    @include('livewire.sandbox.components.199-alert-messages')

    @include('livewire.sandbox.components.200-directory-buttons')

    <div class="grid grid-cols-3 gap-6">
        @include('livewire.sandbox.components.201-file-list')

        <!-- 파일 편집기와 미리보기 -->
        <div class="col-span-2 grid grid-cols-2 gap-6">
            @include('livewire.sandbox.components.202-file-editor')

            @include('livewire.sandbox.components.203-file-preview')
        </div>
    </div>
</div>