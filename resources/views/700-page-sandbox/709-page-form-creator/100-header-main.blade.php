<div class="bg-white border-b border-gray-200 h-16">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Left Side - Logo and Title -->
        <div class="flex items-center space-x-4">
            <a href="/sandbox" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span class="text-sm">Back to Sandbox</span>
            </a>
            <div class="h-6 border-l border-gray-300"></div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-magic text-green-600 text-lg"></i>
                <h1 class="text-lg font-semibold text-gray-900">Form Creator</h1>
                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">BETA</span>
            </div>
        </div>
        
        <!-- Right Side - Actions -->
        <div class="flex items-center space-x-3">
            <!-- Form Manager Button -->
            <button id="btn-form-manager" class="flex items-center space-x-2 px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-folder"></i>
                <span>Forms</span>
            </button>
            
            <!-- Import Button -->
            <button id="btn-import" class="flex items-center space-x-2 px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-upload"></i>
                <span>Import</span>
            </button>
            
            <!-- Export Button -->
            <button id="btn-export-json" class="flex items-center space-x-2 px-3 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition-colors">
                <i class="fas fa-download"></i>
                <span>Export JSON</span>
            </button>
            
            <!-- Save Button -->
            <button id="btn-save-form" class="flex items-center space-x-2 px-4 py-2 bg-green-500 text-white rounded-md text-sm hover:bg-green-600 transition-colors font-medium">
                <i class="fas fa-save"></i>
                <span>Save Form</span>
            </button>
        </div>
    </div>
</div>

<!-- Hidden File Input for Import -->
<input type="file" id="import-file-input" accept=".json" class="hidden">