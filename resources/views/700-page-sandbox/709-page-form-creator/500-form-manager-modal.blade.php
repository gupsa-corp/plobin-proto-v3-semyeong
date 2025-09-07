<!-- Form Manager Modal -->
<div id="form-manager-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Form Manager</h3>
            <button id="close-form-manager" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="mt-4">
            <!-- Action Buttons -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex space-x-3">
                    <button id="btn-new-form" class="bg-green-500 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        New Form
                    </button>
                    <button id="btn-import-form" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-50 transition-colors">
                        <i class="fas fa-upload mr-2"></i>
                        Import JSON
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="text" id="search-forms" placeholder="Search forms..." 
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64">
                    <button id="btn-refresh-forms" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
            </div>
            
            <!-- Forms List -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between text-sm font-medium text-gray-700">
                        <div class="flex-1">Form Name</div>
                        <div class="w-32">Created</div>
                        <div class="w-32">Modified</div>
                        <div class="w-32">Components</div>
                        <div class="w-32">Actions</div>
                    </div>
                </div>
                
                <!-- Forms List Container -->
                <div id="forms-list" class="max-h-96 overflow-y-auto">
                    <!-- Loading State -->
                    <div id="forms-loading" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Loading forms...</p>
                    </div>
                    
                    <!-- Empty State -->
                    <div id="forms-empty" class="text-center py-8 hidden">
                        <i class="fas fa-file-alt text-3xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No forms found</p>
                        <p class="text-gray-400 text-sm">Create your first form to get started</p>
                    </div>
                    
                    <!-- Forms will be dynamically loaded here -->
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
            <button id="btn-close-manager" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Form Item Template (Hidden) -->
<div id="form-item-template" class="hidden">
    <div class="form-item px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center">
                    <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                    <div>
                        <h4 class="form-name text-sm font-medium text-gray-900">Form Name</h4>
                        <p class="form-description text-xs text-gray-500 mt-1">Form description...</p>
                    </div>
                </div>
            </div>
            <div class="w-32 text-sm text-gray-600 form-created">
                <!-- Created date -->
            </div>
            <div class="w-32 text-sm text-gray-600 form-modified">
                <!-- Modified date -->
            </div>
            <div class="w-32 text-sm text-gray-600 form-components">
                <!-- Component count -->
            </div>
            <div class="w-32 flex items-center space-x-2">
                <button class="btn-load-form text-blue-600 hover:text-blue-800 text-sm" title="Load Form">
                    <i class="fas fa-folder-open"></i>
                </button>
                <button class="btn-export-form text-green-600 hover:text-green-800 text-sm" title="Export JSON">
                    <i class="fas fa-download"></i>
                </button>
                <button class="btn-duplicate-form text-yellow-600 hover:text-yellow-800 text-sm" title="Duplicate">
                    <i class="fas fa-copy"></i>
                </button>
                <button class="btn-delete-form text-red-600 hover:text-red-800 text-sm" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Save Form Modal -->
<div id="save-form-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-1/4 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Save Form</h3>
            <button id="close-save-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mt-4">
            <div class="mb-4">
                <label for="form-name-input" class="block text-sm font-medium text-gray-700 mb-2">Form Name</label>
                <input type="text" id="form-name-input" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Enter form name...">
            </div>
            
            <div class="mb-4">
                <label for="form-description-input" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                <textarea id="form-description-input" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="3" placeholder="Enter form description..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button id="btn-save-confirm" class="flex-1 bg-green-500 text-white py-2 rounded-md text-sm hover:bg-green-600 transition-colors">
                    Save Form
                </button>
                <button id="btn-save-cancel" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>