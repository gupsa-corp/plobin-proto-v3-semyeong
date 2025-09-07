<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Form Builder</h2>
        <p class="text-sm text-gray-600">좌측 컴포넌트를 우측 폼 영역으로 드래그하세요</p>
    </div>
    
    <div class="flex">
        <!-- Component Palette - 너비 확장 -->
        <div class="w-80 p-4 border-r border-gray-200 bg-gray-50 max-h-96 overflow-y-auto">
            <h3 class="font-medium text-gray-900 mb-4">Form Components</h3>
            
            <!-- Basic Input Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">📝 Basic Input</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="text" data-label="Text Input">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                            <span class="text-xs">Text</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="email" data-label="Email">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                            <span class="text-xs">Email</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="password" data-label="Password">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                            <span class="text-xs">Password</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="textarea" data-label="Textarea">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                            <span class="text-xs">Textarea</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="number" data-label="Number">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded mr-2"></div>
                            <span class="text-xs">Number</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="tel" data-label="Phone Number">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-600 rounded mr-2"></div>
                            <span class="text-xs">Phone</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="url" data-label="URL">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-indigo-600 rounded mr-2"></div>
                            <span class="text-xs">URL</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="hidden" data-label="Hidden Field">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded mr-2"></div>
                            <span class="text-xs">Hidden</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Selection Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">☑️ Selection</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="select" data-label="Select Dropdown">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-indigo-500 rounded mr-2"></div>
                            <span class="text-xs">Select</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="multiselect" data-label="Multi Select">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-indigo-600 rounded mr-2"></div>
                            <span class="text-xs">Multi Select</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="radio" data-label="Radio Button">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-pink-500 rounded mr-2"></div>
                            <span class="text-xs">Radio</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="checkbox" data-label="Checkbox">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-teal-500 rounded mr-2"></div>
                            <span class="text-xs">Checkbox</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="switch" data-label="Toggle Switch">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-600 rounded mr-2"></div>
                            <span class="text-xs">Switch</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="rating" data-label="Star Rating">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-400 rounded mr-2"></div>
                            <span class="text-xs">Rating</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Date & Time Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">🗓️ Date & Time</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="date" data-label="Date">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-cyan-500 rounded mr-2"></div>
                            <span class="text-xs">Date</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="time" data-label="Time">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-cyan-600 rounded mr-2"></div>
                            <span class="text-xs">Time</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="datetime" data-label="Date Time">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-cyan-700 rounded mr-2"></div>
                            <span class="text-xs">DateTime</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="daterange" data-label="Date Range">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-cyan-400 rounded mr-2"></div>
                            <span class="text-xs">Date Range</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Media & File Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">📎 Media & Files</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="file" data-label="File Upload">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded mr-2"></div>
                            <span class="text-xs">File</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="image" data-label="Image Upload">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-600 rounded mr-2"></div>
                            <span class="text-xs">Image</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="camera" data-label="Camera Capture">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-600 rounded mr-2"></div>
                            <span class="text-xs">Camera</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="signature" data-label="Signature Pad">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-600 rounded mr-2"></div>
                            <span class="text-xs">Signature</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Advanced Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">⚙️ Advanced</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="range" data-label="Range Slider">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-700 rounded mr-2"></div>
                            <span class="text-xs">Range</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="color" data-label="Color Picker">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-pink-400 rounded mr-2"></div>
                            <span class="text-xs">Color</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="captcha" data-label="CAPTCHA">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-700 rounded mr-2"></div>
                            <span class="text-xs">CAPTCHA</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="location" data-label="Location Picker">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-700 rounded mr-2"></div>
                            <span class="text-xs">Location</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Layout & Display Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">🎨 Layout & Display</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="heading" data-label="Heading">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-700 rounded mr-2"></div>
                            <span class="text-xs">Heading</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="paragraph" data-label="Paragraph">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-600 rounded mr-2"></div>
                            <span class="text-xs">Paragraph</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="divider" data-label="Divider">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-500 rounded mr-2"></div>
                            <span class="text-xs">Divider</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="spacer" data-label="Spacer">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded mr-2"></div>
                            <span class="text-xs">Spacer</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="html" data-label="HTML Block">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-700 rounded mr-2"></div>
                            <span class="text-xs">HTML</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="image-display" data-label="Image Display">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-800 rounded mr-2"></div>
                            <span class="text-xs">Image</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Components -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">🔘 Actions</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="button" data-label="Button">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-500 rounded mr-2"></div>
                            <span class="text-xs">Button</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="submit" data-label="Submit Button">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                            <span class="text-xs">Submit</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="reset" data-label="Reset Button">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                            <span class="text-xs">Reset</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="link" data-label="Link">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-400 rounded mr-2"></div>
                            <span class="text-xs">Link</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Container Components -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">📦 Containers</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="fieldset" data-label="Fieldset">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-indigo-700 rounded mr-2"></div>
                            <span class="text-xs">Fieldset</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="panel" data-label="Panel">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-indigo-800 rounded mr-2"></div>
                            <span class="text-xs">Panel</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="tabs" data-label="Tabs">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-700 rounded mr-2"></div>
                            <span class="text-xs">Tabs</span>
                        </div>
                    </div>
                    
                    <div class="component-item bg-white p-2 rounded border border-gray-200 cursor-move hover:bg-blue-50 hover:border-blue-300" 
                         data-type="columns" data-label="Columns">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-800 rounded mr-2"></div>
                            <span class="text-xs">Columns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Drop Area -->
        <div class="flex-1 p-4">
            <div id="form-builder-area" class="min-h-96 border-2 border-dashed border-gray-300 rounded-lg p-4 bg-white">
                <div class="text-center text-gray-400 py-16" id="empty-state">
                    <div class="text-4xl mb-4">📋</div>
                    <p class="text-lg">여기에 컴포넌트를 드래그하세요</p>
                    <p class="text-sm">좌측 팔레트에서 원하는 컴포넌트를 드래그해서 폼을 구성하세요</p>
                </div>
                
                <div id="form-components" class="space-y-4 hidden">
                    <!-- 드래그된 컴포넌트들이 여기에 표시됩니다 -->
                </div>
            </div>
        </div>
    </div>
</div>