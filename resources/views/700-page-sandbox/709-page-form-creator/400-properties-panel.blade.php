<div class="h-full flex flex-col">
    <!-- Right Panel Header with Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex">
            <button class="right-panel-tab active flex-1 text-center py-3 px-1 text-xs font-medium border-b-2 border-blue-500 text-blue-600" 
                    data-tab="components">
                <i class="fas fa-puzzle-piece block mb-1"></i>
                Components
            </button>
            <button class="right-panel-tab flex-1 text-center py-3 px-1 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                    data-tab="tree">
                <i class="fas fa-sitemap block mb-1"></i>
                Tree
            </button>
            <button class="right-panel-tab flex-1 text-center py-3 px-1 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                    data-tab="settings">
                <i class="fas fa-cog block mb-1"></i>
                Settings
            </button>
            <button class="right-panel-tab flex-1 text-center py-3 px-1 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                    data-tab="forms">
                <i class="fas fa-folder block mb-1"></i>
                Forms
            </button>
        </nav>
    </div>
    
    <!-- Right Panel Content -->
    <div class="flex-1 overflow-y-auto scrollable-content">
        
        <!-- Components Tab Content -->
        <div id="right-tab-components" class="right-tab-content">
        <!-- No Selection State -->
        <div id="no-selection" class="p-4 text-center text-gray-500">
            <i class="fas fa-mouse-pointer text-3xl mb-3 text-gray-300"></i>
            <p class="text-sm">No component selected</p>
            <p class="text-xs text-gray-400 mt-1">Click on a component to edit its properties</p>
        </div>
        
        <!-- Component Properties Form -->
        <div id="component-properties" class="hidden">
            <!-- Property Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button class="property-tab active flex-1 text-center py-2 px-1 text-sm font-medium border-b-2 border-blue-500 text-blue-600" 
                            data-tab="main">
                        Main
                    </button>
                    <button class="property-tab flex-1 text-center py-2 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-tab="style">
                        Style
                    </button>
                    <button class="property-tab flex-1 text-center py-2 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-tab="actions">
                        Actions
                    </button>
                    <button class="property-tab flex-1 text-center py-2 px-1 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-tab="rules">
                        Rules
                    </button>
                </nav>
            </div>
            
            <!-- Tab Contents -->
            <div class="p-4">
                <!-- Main Properties Tab -->
                <div id="tab-main" class="tab-content">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Component Type</label>
                            <input type="text" id="prop-type" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm bg-gray-100" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <input type="text" id="prop-label" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" placeholder="Enter label...">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" id="prop-name" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" placeholder="field_name">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Placeholder</label>
                            <input type="text" id="prop-placeholder" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" placeholder="Enter placeholder...">
                        </div>
                        
                        <div>
                            <label class="block text-sm fonㄴt-medium text-gray-700 mb-1">Description</label>
                            <textarea id="prop-description" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" rows="3" placeholder="Enter description..."></textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="prop-required" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="prop-required" class="ml-2 text-sm text-gray-700">Required</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="prop-disabled" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="prop-disabled" class="ml-2 text-sm text-gray-700">Disabled</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="prop-hidden" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="prop-hidden" class="ml-2 text-sm text-gray-700">Hidden</label>
                        </div>
                    </div>
                </div>
                
                <!-- Style Properties Tab -->
                <div id="tab-style" class="tab-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Width</label>
                            <select id="prop-width" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm">
                                <option value="full">Full Width</option>
                                <option value="half">Half Width</option>
                                <option value="third">One Third</option>
                                <option value="quarter">One Quarter</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Margin</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" id="prop-margin-top" class="border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Top" min="0">
                                <input type="number" id="prop-margin-bottom" class="border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Bottom" min="0">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Padding</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" id="prop-padding-x" class="border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Horizontal" min="0">
                                <input type="number" id="prop-padding-y" class="border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Vertical" min="0">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                            <input type="color" id="prop-bg-color" class="w-full h-10 border border-gray-300 rounded-md" value="#ffffff">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                            <input type="color" id="prop-text-color" class="w-full h-10 border border-gray-300 rounded-md" value="#000000">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Border</label>
                            <div class="space-y-2">
                                <select id="prop-border-style" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm">
                                    <option value="none">None</option>
                                    <option value="solid">Solid</option>
                                    <option value="dashed">Dashed</option>
                                    <option value="dotted">Dotted</option>
                                </select>
                                <input type="number" id="prop-border-width" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Border width" min="0">
                                <input type="color" id="prop-border-color" class="w-full h-8 border border-gray-300 rounded-md" value="#000000">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions Properties Tab -->
                <div id="tab-actions" class="tab-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">onClick Action</label>
                            <select id="prop-onclick" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm">
                                <option value="">None</option>
                                <option value="submit">Submit Form</option>
                                <option value="reset">Reset Form</option>
                                <option value="custom">Custom Script</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">onChange Action</label>
                            <select id="prop-onchange" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm">
                                <option value="">None</option>
                                <option value="validate">Validate Field</option>
                                <option value="calculate">Calculate Value</option>
                                <option value="custom">Custom Script</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Custom JavaScript</label>
                            <textarea id="prop-custom-js" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm font-mono" rows="6" placeholder="// Enter custom JavaScript code"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Rules Properties Tab -->
                <div id="tab-rules" class="tab-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Validation Rules</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="rule-required" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <label for="rule-required" class="ml-2 text-sm text-gray-700">Required</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="rule-email" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <label for="rule-email" class="ml-2 text-sm text-gray-700">Email Format</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="rule-numeric" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <label for="rule-numeric" class="ml-2 text-sm text-gray-700">Numeric Only</label>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Length</label>
                            <input type="number" id="rule-min-length" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" min="0">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Length</label>
                            <input type="number" id="rule-max-length" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" min="0">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pattern (RegEx)</label>
                            <input type="text" id="rule-pattern" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm font-mono" placeholder="^[a-zA-Z0-9]*$">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Error Message</label>
                            <input type="text" id="rule-error-message" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" placeholder="Enter error message...">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Conditional Logic</label>
                            <div class="space-y-2">
                                <select id="rule-condition-field" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm">
                                    <option value="">Select Field</option>
                                </select>
                                <select id="rule-condition-operator" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm">
                                    <option value="equals">Equals</option>
                                    <option value="not-equals">Not Equals</option>
                                    <option value="contains">Contains</option>
                                    <option value="greater-than">Greater Than</option>
                                    <option value="less-than">Less Than</option>
                                </select>
                                <input type="text" id="rule-condition-value" class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" placeholder="Condition value">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Property Actions -->
            <div class="border-t border-gray-200 p-4">
                <div class="flex space-x-2">
                    <button id="apply-properties" class="flex-1 bg-blue-500 text-white py-2 px-3 rounded-md text-sm hover:bg-blue-600 transition-colors">
                        Apply Changes
                    </button>
                    <button id="reset-properties" class="px-3 py-2 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition-colors">
                        Reset
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Tree Tab Content -->
        <div id="right-tab-tree" class="right-tab-content hidden">
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-gray-900">Form Structure</h4>
                    <button id="expand-all-tree" class="text-xs text-blue-600 hover:text-blue-800">
                        <i class="fas fa-expand-arrows-alt"></i> Expand All
                    </button>
                </div>
                
                <div id="form-tree" class="space-y-1">
                    <div class="tree-item" data-level="0">
                        <div class="flex items-center py-1 px-2 text-sm text-gray-600 bg-gray-50 rounded">
                            <i class="fas fa-file-alt mr-2 text-gray-400"></i>
                            <span class="font-medium">Form Root</span>
                            <span class="ml-auto text-xs text-gray-400" id="tree-component-count">0 components</span>
                        </div>
                    </div>
                    
                    <div id="tree-components" class="ml-4 space-y-1">
                        <!-- Tree items will be dynamically generated -->
                    </div>
                    
                    <div id="tree-empty" class="text-center py-8 text-gray-400">
                        <i class="fas fa-sitemap text-2xl mb-2"></i>
                        <p class="text-sm">No components in form</p>
                        <p class="text-xs">Add components to see structure</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Settings Tab Content -->
        <div id="right-tab-settings" class="right-tab-content hidden">
            <div class="p-4 space-y-6">
                <!-- Form Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Form Settings</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Form Title</label>
                            <input type="text" id="settings-form-title" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Enter form title...">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Form Description</label>
                            <textarea id="settings-form-description" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="3" placeholder="Enter form description..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submit Button Text</label>
                            <input type="text" id="settings-submit-text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" value="Submit" placeholder="Submit">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Success Message</label>
                            <input type="text" id="settings-success-message" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Form submitted successfully!">
                        </div>
                    </div>
                </div>
                
                <!-- Layout Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Layout Settings</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Layout Style</label>
                            <select id="settings-layout-style" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="vertical">Vertical</option>
                                <option value="horizontal">Horizontal</option>
                                <option value="inline">Inline</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Theme</label>
                            <select id="settings-theme" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="default">Default</option>
                                <option value="minimal">Minimal</option>
                                <option value="modern">Modern</option>
                                <option value="classic">Classic</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="settings-show-progress" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="settings-show-progress" class="ml-2 text-sm text-gray-700">Show progress bar</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="settings-show-labels" class="h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                            <label for="settings-show-labels" class="ml-2 text-sm text-gray-700">Show field labels</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="settings-required-asterisk" class="h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                            <label for="settings-required-asterisk" class="ml-2 text-sm text-gray-700">Show asterisk for required fields</label>
                        </div>
                    </div>
                </div>
                
                <!-- Validation Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Validation Settings</h4>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="settings-validate-realtime" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="settings-validate-realtime" class="ml-2 text-sm text-gray-700">Real-time validation</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="settings-show-errors" class="h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                            <label for="settings-show-errors" class="ml-2 text-sm text-gray-700">Show error messages</label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Error Display Position</label>
                            <select id="settings-error-position" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="below">Below field</option>
                                <option value="above">Above field</option>
                                <option value="inline">Inline with field</option>
                                <option value="tooltip">Tooltip</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex space-x-2">
                        <button id="apply-settings" class="flex-1 bg-blue-500 text-white py-2 px-3 rounded-md text-sm hover:bg-blue-600 transition-colors">
                            Apply Settings
                        </button>
                        <button id="reset-settings" class="px-3 py-2 border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition-colors">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Forms Tab Content -->
        <div id="right-tab-forms" class="right-tab-content hidden">
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-900">Saved Forms</h4>
                    <button id="refresh-forms-list" class="text-xs text-blue-600 hover:text-blue-800">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                
                <!-- Search Forms -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="quick-search-forms" class="w-full border border-gray-300 rounded-md px-3 py-2 pl-9 text-sm" placeholder="Search forms...">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Forms List -->
                <div id="quick-forms-list" class="space-y-2">
                    <!-- Quick forms list items will be dynamically generated -->
                </div>
                
                <!-- Empty State -->
                <div id="quick-forms-empty" class="text-center py-8 text-gray-400">
                    <i class="fas fa-folder-open text-2xl mb-2"></i>
                    <p class="text-sm">No saved forms</p>
                    <p class="text-xs">Create and save forms to see them here</p>
                </div>
                
                <!-- Quick Actions -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="space-y-2">
                        <button id="quick-new-form" class="w-full bg-green-500 text-white py-2 px-3 rounded-md text-sm hover:bg-green-600 transition-colors flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>
                            New Form
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button id="quick-import-form" class="bg-gray-100 text-gray-700 py-2 px-3 rounded-md text-sm hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-upload mr-1"></i>
                                Import
                            </button>
                            <button id="quick-export-current" class="bg-gray-100 text-gray-700 py-2 px-3 rounded-md text-sm hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-download mr-1"></i>
                                Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>