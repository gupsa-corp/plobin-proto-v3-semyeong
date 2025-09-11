<div class="h-full flex flex-col">
    <!-- Form Builder Header with Tabs -->
    <div class="border-b border-gray-200">
        <div class="flex items-center justify-between px-4 py-2 bg-gray-50">
            <div class="flex items-center space-x-4">
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600" id="btn-preview">
                    <i class="fas fa-eye mr-1" id="preview-icon"></i>
                    <span id="preview-text">Preview</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Form Builder Canvas -->
    <div class="flex-1 p-6 bg-white relative">
        <!-- Drop Zone -->
        <div id="form-canvas" class="min-h-full border-2 border-dashed border-gray-300 rounded-lg p-4 transition-colors bg-gray-50">
            <div class="text-center text-gray-500 py-12" id="empty-state">
                <i class="fas fa-mouse-pointer text-4xl mb-4 text-gray-400"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Start Building Your Form</h3>
                <p class="text-gray-600">Drag components from the left panel to create your form</p>
            </div>

            <!-- Dynamic Form Components Will Be Added Here -->
            <div id="form-components" class="space-y-4 hidden">
                <!-- Components will be dynamically added here -->
            </div>
        </div>

        <!-- Floating Action Buttons -->
        <div class="absolute bottom-6 right-6 space-y-2">
            <button class="w-12 h-12 bg-blue-500 text-white rounded-full shadow-lg hover:bg-blue-600 transition-colors"
                    id="btn-save" title="Save Form">
                <i class="fas fa-save"></i>
            </button>
            <button class="w-12 h-12 bg-green-500 text-white rounded-full shadow-lg hover:bg-green-600 transition-colors"
                    id="btn-export" title="Export JSON">
                <i class="fas fa-download"></i>
            </button>
            <button class="w-12 h-12 bg-orange-500 text-white rounded-full shadow-lg hover:bg-orange-600 transition-colors"
                    id="btn-clear" title="Clear All">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    <!-- Bottom Status Bar -->
    <div class="border-t border-gray-200 px-4 py-2 bg-gray-50 text-sm text-gray-600 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span id="component-count">Components: 0</span>
            <span class="text-gray-400">|</span>
            <span id="form-status">Ready</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-gray-500">Auto-save:</span>
            <span class="w-2 h-2 bg-green-400 rounded-full" id="autosave-indicator"></span>
        </div>
    </div>
</div>

<!-- Hidden Template for Form Components -->
<div class="hidden" id="component-templates">
    <!-- Input Component Template -->
    <div class="component-wrapper" data-type="input">
        <div class="bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
            <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent(this)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Text Input</label>
            <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Enter text...">
        </div>
    </div>

    <!-- Button Component Template -->
    <div class="component-wrapper" data-type="button">
        <div class="bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
            <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent(this)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                Button
            </button>
        </div>
    </div>

    <!-- Header Component Template -->
    <div class="component-wrapper" data-type="header">
        <div class="bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
            <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent(this)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Header Text</h2>
        </div>
    </div>
</div>
