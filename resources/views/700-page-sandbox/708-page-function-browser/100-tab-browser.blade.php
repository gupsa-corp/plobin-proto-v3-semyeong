<div
    x-data="{
        sidebarWidth: 320,
        previewWidth: 35, // percentage
        isResizingSidebar: false,
        isResizingPreview: false,
        testParams: '{}',
        
        startSidebarResize(e) {
            this.isResizingSidebar = true;
            document.addEventListener('mousemove', this.handleSidebarResize);
            document.addEventListener('mouseup', this.stopSidebarResize);
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
            e.preventDefault();
        },

        handleSidebarResize(e) {
            if (this.isResizingSidebar) {
                const newWidth = Math.max(250, Math.min(600, e.clientX));
                this.sidebarWidth = newWidth;
            }
        },

        stopSidebarResize() {
            this.isResizingSidebar = false;
            document.removeEventListener('mousemove', this.handleSidebarResize);
            document.removeEventListener('mouseup', this.stopSidebarResize);
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        },

        startPreviewResize(e) {
            this.isResizingPreview = true;
            document.addEventListener('mousemove', this.handlePreviewResize);
            document.addEventListener('mouseup', this.stopPreviewResize);
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
            e.preventDefault();
        },

        handlePreviewResize(e) {
            if (this.isResizingPreview) {
                const containerWidth = window.innerWidth - this.sidebarWidth;
                const previewX = e.clientX - this.sidebarWidth;
                const percentage = Math.max(20, Math.min(80, (containerWidth - previewX) / containerWidth * 100));
                this.previewWidth = percentage;
            }
        },

        stopPreviewResize() {
            this.isResizingPreview = false;
            document.removeEventListener('mousemove', this.handlePreviewResize);
            document.removeEventListener('mouseup', this.stopPreviewResize);
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        }
    }"
    class="h-full flex"
>
    {{-- Include the existing function browser content --}}
    @include('livewire.sandbox.partials.function-browser-content')
</div>