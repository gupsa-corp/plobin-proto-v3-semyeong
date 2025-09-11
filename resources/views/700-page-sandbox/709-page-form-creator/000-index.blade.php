<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormEngine Demo - Sandbox</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery for better compatibility -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI for drag and drop -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <!-- SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <!-- FormIO for form preview -->
    <script src="https://unpkg.com/formiojs@latest/dist/formio.full.min.js"></script>
    <link href="https://unpkg.com/formiojs@latest/dist/formio.full.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles that can't be easily replaced with Tailwind */
        .form-builder-layout {
            height: calc(100vh - 60px);
        }
        .scrollable-content {
            max-height: calc(100vh - 140px);
        }
        .tree-item[data-level="1"] {
            margin-left: 1rem;
        }
        .tree-item:hover .group-hover\:opacity-100 {
            opacity: 1 !important;
        }
        
        /* Form Canvas Themes */
        .theme-minimal #form-components .component-wrapper > div {
            border: 1px solid #e5e7eb;
            border-radius: 0.25rem;
            box-shadow: none;
        }
        .theme-modern #form-components .component-wrapper > div {
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .theme-classic #form-components .component-wrapper > div {
            border: 2px solid #374151;
            border-radius: 0;
            background-color: #f9fafb;
        }
        
        /* Layout Styles */
        .layout-horizontal #form-components {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .layout-horizontal #form-components .component-wrapper {
            flex: 1;
            margin-bottom: 0;
            min-width: 200px;
        }
        .layout-inline #form-components {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .layout-inline #form-components .component-wrapper {
            flex: 0 0 auto;
            margin-bottom: 0;
        }
        .layout-inline #form-components .component-wrapper > div {
            padding: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Top Navigation Bar -->
    @include('700-page-sandbox.709-page-form-creator.100-header-main')
    
    <!-- Main Form Builder Layout -->
    <div class="form-builder-layout flex overflow-hidden">
        <!-- Left Panel - Components Palette -->
        <div class="w-80 bg-white border-r border-gray-200">
            @include('700-page-sandbox.709-page-form-creator.200-components-palette')
        </div>
        
        <!-- Center Panel - Form Builder Area -->
        <div class="flex-1 bg-gray-50">
            @include('700-page-sandbox.709-page-form-creator.300-form-builder-area')
        </div>
        
        <!-- Right Panel - Properties Panel -->
        <div class="w-80 bg-white border-l border-gray-200">
            @include('700-page-sandbox.709-page-form-creator.400-properties-panel')
        </div>
    </div>
    
    <!-- Preview Panel Modal - Hidden by default -->
    <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-5 hidden" id="preview-container">
        <div class="bg-white rounded-lg shadow-lg max-h-[90vh] max-w-[90vw] w-full overflow-y-auto" id="preview-panel">
            @include('700-page-sandbox.709-page-form-creator.400-form-preview')
        </div>
    </div>
    
    <!-- Hidden Form Manager Modal -->
    @include('700-page-sandbox.709-page-form-creator.500-form-manager-modal')

    @include('700-page-sandbox.709-page-form-creator.900-scripts')
</body>
</html>