<script>
$(document).ready(function() {
    // Global form data
    let formData = {
        name: '',
        description: '',
        components: [],
        settings: {}
    };
    
    let selectedComponent = null;
    let componentCounter = 0;
    
    // Initialize drag and drop
    initializeDragDrop();
    
    // Initialize sortable
    initializeSortable();
    
    // Initialize event handlers
    initializeEventHandlers();
    
    // Load saved forms list
    loadFormsList();
    
    /**
     * Initialize drag and drop functionality
     */
    function initializeDragDrop() {
        // Destroy existing draggable instances first
        try {
            $('.component-item').draggable('destroy');
        } catch(e) {
            // Ignore if not initialized
        }
        
        // Make components draggable
        $('.component-item').draggable({
            helper: 'clone',
            revert: 'invalid',
            zIndex: 1000,
            cursor: 'move',
            cursorAt: { top: 5, left: 5 },
            distance: 5,
            start: function(event, ui) {
                $(ui.helper).addClass('dragging');
                // Copy component type data to the helper
                $(ui.helper).data('component-type', $(this).data('component-type'));
            }
        });
        
        // Make form canvas droppable for new components
        $('#form-canvas').droppable({
            accept: '.component-item',
            drop: function(event, ui) {
                const componentType = $(ui.helper).data('component-type');
                
                if (!componentType) {
                    // Fallback: try to get from original draggable element
                    const fallbackType = ui.draggable.data('component-type');
                    addComponentToCanvas(fallbackType);
                } else {
                    addComponentToCanvas(componentType);
                }
                hideEmptyState();
            },
            over: function(event, ui) {
                $(this).addClass('drag-over');
            },
            out: function(event, ui) {
                $(this).removeClass('drag-over');
            }
        });
    }
    
    /**
     * Initialize sortable for component reordering
     */
    function initializeSortable() {
        // Destroy existing sortable first
        try {
            $('#form-components').sortable('destroy');
        } catch(e) {
            // Ignore if not initialized
        }
        
        // Make form components container sortable for reordering components
        $('#form-components').sortable({
            items: '.component-wrapper',
            handle: '.component-card',
            placeholder: 'sortable-placeholder',
            tolerance: 'pointer',
            cursor: 'move',
            distance: 5,
            start: function(event, ui) {
                $(ui.item).addClass('dragging');
            },
            stop: function(event, ui) {
                $(ui.item).removeClass('dragging');
            },
            update: function(event, ui) {
                updateComponentOrder();
            }
        });
        
        // Apply dynamic styles for form builder
        if (!$('#form-builder-styles').length) {
            $('<style id="form-builder-styles">')
                .prop('type', 'text/css')
                .html(`
                    .sortable-placeholder {
                        border-bottom: 2px dashed #3b82f6;
                        background-color: #dbeafe;
                        height: 1px;
                        margin: 4px 0;
                        border-radius: 8px;
                    }
                    .component-wrapper.dragging {
                        opacity: 0.8;
                        transform: rotate(2deg);
                        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                        z-index: 1000;
                    }
                    .component-card {
                        cursor: move;
                    }
                    .drag-over {
                        background-color: #f0f9ff !important;
                        border-color: #3b82f6 !important;
                    }
                    .dragging {
                        opacity: 0.8;
                        transform: rotate(5deg);
                    }
                    .component-item {
                        user-select: none;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                    }
                    .component-item * {
                        pointer-events: none;
                    }
                `)
                .appendTo('head');
        }
    }
    
    /**
     * Initialize event handlers
     */
    function initializeEventHandlers() {
        // Right panel tab switching
        $('.right-panel-tab').click(function() {
            const tab = $(this).data('tab');
            switchRightPanelTab(tab);
        });
        
        // Property tab switching (within components tab)
        $('.property-tab').click(function() {
            const tab = $(this).data('tab');
            switchPropertyTab(tab);
        });
        
        // Component selection
        $(document).on('click', '.component-wrapper', function(e) {
            // Don't select if dragging
            if ($(this).hasClass('dragging')) {
                return;
            }
            selectComponent($(this));
        });
        
        // Property changes
        $('#component-properties input, #component-properties select, #component-properties textarea').change(function() {
            updateSelectedComponent();
        });
        
        // Apply properties
        $('#apply-properties').click(function() {
            applyComponentProperties();
        });
        
        // Reset properties
        $('#reset-properties').click(function() {
            resetComponentProperties();
        });
        
        // Form actions
        $('#btn-save').click(saveForm);
        $('#btn-export').click(exportForm);
        $('#btn-clear').click(clearForm);
        
        // Header actions
        $('#btn-form-manager').click(showFormManager);
        $('#btn-import').click(importForm);
        $('#btn-export-json').click(exportForm);
        $('#btn-save-form').click(showSaveModal);
        
        // Form manager modal
        $('#close-form-manager, #btn-close-manager').click(hideFormManager);
        $('#btn-new-form').click(newForm);
        $('#btn-refresh-forms').click(loadFormsList);
        
        // Save modal
        $('#close-save-modal, #btn-save-cancel').click(hideSaveModal);
        $('#btn-save-confirm').click(saveFormWithName);
        
        // Import file
        $('#import-file-input').change(handleImportFile);
        $('#btn-import-form').click(function() {
            $('#import-file-input').click();
        });
        
        // Search forms
        $('#search-forms').on('input', filterForms);
        
        // Right panel quick actions
        $('#quick-search-forms').on('input', quickFilterForms);
        $('#refresh-forms-list').click(loadQuickFormsList);
        $('#quick-new-form').click(newForm);
        $('#quick-import-form').click(importForm);
        $('#quick-export-current').click(exportForm);
        
        // Tree actions
        $('#expand-all-tree').click(expandAllTreeNodes);
        $(document).on('click', '.tree-node-toggle', toggleTreeNode);
        $(document).on('click', '.tree-item', selectTreeItem);
        
        // Settings actions
        $('#apply-settings').click(applyFormSettings);
        $('#reset-settings').click(resetFormSettings);
        
        // Form settings change handlers
        $('#settings-form-title, #settings-form-description, #settings-submit-text, #settings-success-message').on('input', updateFormSettings);
        $('#settings-layout-style, #settings-theme, #settings-error-position').on('change', updateFormSettings);
        $('#settings-show-progress, #settings-show-labels, #settings-required-asterisk, #settings-validate-realtime, #settings-show-errors').on('change', updateFormSettings);
        
        // Form component actions
        $(document).on('click', '.btn-load-form', function() {
            const filename = $(this).closest('.form-item').data('filename');
            loadForm(filename);
        });
        
        $(document).on('click', '.btn-delete-form', function() {
            const filename = $(this).closest('.form-item').data('filename');
            deleteForm(filename);
        });
        
        $(document).on('click', '.btn-export-form', function() {
            const filename = $(this).closest('.form-item').data('filename');
            exportSavedForm(filename);
        });
    }
    
    /**
     * Add component to canvas
     */
    function addComponentToCanvas(componentType) {
        if (!componentType) {
            return;
        }
        
        const componentId = 'comp_' + (++componentCounter);
        let componentHtml = '';
        
        // Generate component based on type
        switch(componentType) {
            case 'input':
                componentHtml = createInputComponent(componentId);
                break;
            case 'button':
                componentHtml = createButtonComponent(componentId);
                break;
            case 'header':
                componentHtml = createHeaderComponent(componentId);
                break;
            case 'textarea':
                componentHtml = createTextareaComponent(componentId);
                break;
            case 'dropdown':
                componentHtml = createDropdownComponent(componentId);
                break;
            case 'checkbox':
                componentHtml = createCheckboxComponent(componentId);
                break;
            case 'radiogroup':
                componentHtml = createRadioGroupComponent(componentId);
                break;
            default:
                componentHtml = createGenericComponent(componentId, componentType);
        }
        
        $('#form-components').append(componentHtml);
        updateComponentCount();
        
        // Add to form data
        formData.components.push({
            id: componentId,
            type: componentType,
            properties: getDefaultProperties(componentType)
        });
        
        // Auto-select the new component
        selectComponent($('#' + componentId));
    }
    
    /**
     * Create input component HTML
     */
    function createInputComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="input">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <label class="component-label block text-sm font-medium text-gray-700 mb-2">Text Input</label>
                    <input type="text" class="component-input w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Enter text...">
                </div>
            </div>
        `;
    }
    
    /**
     * Create button component HTML
     */
    function createButtonComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="button">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <button class="component-button bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                        Button
                    </button>
                </div>
            </div>
        `;
    }
    
    /**
     * Create header component HTML
     */
    function createHeaderComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="header">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <h2 class="component-header text-xl font-bold text-gray-900">Header Text</h2>
                </div>
            </div>
        `;
    }
    
    /**
     * Create textarea component HTML
     */
    function createTextareaComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="textarea">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <label class="component-label block text-sm font-medium text-gray-700 mb-2">Text Area</label>
                    <textarea class="component-textarea w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="4" placeholder="Enter text..."></textarea>
                </div>
            </div>
        `;
    }
    
    /**
     * Create dropdown component HTML
     */
    function createDropdownComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="dropdown">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <label class="component-label block text-sm font-medium text-gray-700 mb-2">Dropdown</label>
                    <select class="component-select w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Select an option</option>
                        <option value="option1">Option 1</option>
                        <option value="option2">Option 2</option>
                        <option value="option3">Option 3</option>
                    </select>
                </div>
            </div>
        `;
    }
    
    /**
     * Create checkbox component HTML
     */
    function createCheckboxComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="checkbox">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" class="component-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label class="component-label ml-2 text-sm text-gray-700">Checkbox Option</label>
                    </div>
                </div>
            </div>
        `;
    }
    
    /**
     * Create radio group component HTML
     */
    function createRadioGroupComponent(componentId) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="radiogroup">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <fieldset>
                        <legend class="component-label text-sm font-medium text-gray-700 mb-2">Radio Group</legend>
                        <div class="component-radios space-y-2">
                            <div class="flex items-center">
                                <input type="radio" name="${componentId}_radio" class="h-4 w-4 text-blue-600 border-gray-300" value="option1">
                                <label class="ml-2 text-sm text-gray-700">Option 1</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="${componentId}_radio" class="h-4 w-4 text-blue-600 border-gray-300" value="option2">
                                <label class="ml-2 text-sm text-gray-700">Option 2</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="${componentId}_radio" class="h-4 w-4 text-blue-600 border-gray-300" value="option3">
                                <label class="ml-2 text-sm text-gray-700">Option 3</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        `;
    }
    
    /**
     * Create generic component HTML
     */
    function createGenericComponent(componentId, componentType) {
        return `
            <div id="${componentId}" class="component-wrapper mb-4" data-type="${componentType}">
                <div class="component-card bg-white border border-gray-200 rounded-md p-4 hover:border-blue-400 transition-colors relative group">
                    <div class="component-toolbar absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-6 h-6 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 mr-1" onclick="editComponent('${componentId}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="w-6 h-6 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="deleteComponent('${componentId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-puzzle-piece text-2xl mb-2"></i>
                        <p class="text-sm font-medium">${componentType.charAt(0).toUpperCase() + componentType.slice(1)}</p>
                        <p class="text-xs">Component placeholder</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    /**
     * Get default properties for component type
     */
    function getDefaultProperties(componentType) {
        const defaults = {
            input: { label: 'Text Input', name: 'text_input', placeholder: 'Enter text...', required: false },
            button: { label: 'Button', text: 'Button', type: 'button' },
            header: { text: 'Header Text', level: 'h2' },
            textarea: { label: 'Text Area', name: 'text_area', placeholder: 'Enter text...', rows: 4 },
            dropdown: { label: 'Dropdown', name: 'dropdown', options: ['Option 1', 'Option 2', 'Option 3'] },
            checkbox: { label: 'Checkbox Option', name: 'checkbox', value: 'option' },
            radiogroup: { label: 'Radio Group', name: 'radio_group', options: ['Option 1', 'Option 2', 'Option 3'] }
        };
        
        return defaults[componentType] || { label: componentType, name: componentType };
    }
    
    /**
     * Select component
     */
    function selectComponent($component) {
        // Remove previous selection
        $('.component-wrapper').removeClass('selected');
        
        // Add selection
        $component.addClass('selected');
        selectedComponent = $component;
        
        // Show properties panel
        showComponentProperties($component);
    }
    
    /**
     * Show component properties
     */
    function showComponentProperties($component) {
        const componentType = $component.data('type');
        const componentId = $component.attr('id');
        
        $('#no-selection').hide();
        $('#component-properties').show();
        
        // Fill basic properties
        $('#prop-type').val(componentType);
        
        // Fill component-specific properties
        fillComponentProperties($component, componentType);
    }
    
    /**
     * Convert RGB color to hex format
     */
    function rgbToHex(rgb) {
        if (!rgb) return '';
        
        // If already hex format, return as is
        if (rgb.startsWith('#')) return rgb;
        
        // If RGB format, convert to hex
        if (rgb.startsWith('rgb(')) {
            const matches = rgb.match(/\d+/g);
            if (matches && matches.length >= 3) {
                const r = parseInt(matches[0]);
                const g = parseInt(matches[1]);
                const b = parseInt(matches[2]);
                return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
            }
        }
        
        return rgb;
    }

    /**
     * Component property definitions - which properties each component type should show
     */
    const componentPropertyDefinitions = {
        input: {
            main: ['label', 'name', 'placeholder', 'description', 'required', 'disabled', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: ['onclick', 'onchange'],
            rules: ['ruleRequired', 'ruleMinLength', 'ruleMaxLength', 'rulePattern', 'ruleNumeric', 'ruleEmail', 'ruleErrorMessage']
        },
        button: {
            main: ['label', 'text', 'button-type', 'disabled', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: ['onclick'],
            rules: []
        },
        header: {
            main: ['text', 'level', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: [],
            rules: []
        },
        textarea: {
            main: ['label', 'name', 'placeholder', 'description', 'rows', 'required', 'disabled', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: ['onclick', 'onchange'],
            rules: ['ruleRequired', 'ruleMinLength', 'ruleMaxLength', 'rulePattern', 'ruleErrorMessage']
        },
        dropdown: {
            main: ['label', 'name', 'options', 'required', 'disabled', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: ['onchange'],
            rules: ['ruleRequired', 'ruleErrorMessage']
        },
        checkbox: {
            main: ['label', 'name', 'value', 'required', 'disabled', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: ['onchange'],
            rules: ['ruleRequired', 'ruleErrorMessage']
        },
        radiogroup: {
            main: ['label', 'name', 'options', 'required', 'disabled', 'hidden'],
            style: ['width', 'margin-top', 'margin-bottom', 'padding-x', 'padding-y', 'bg-color', 'text-color', 'border-style', 'border-width', 'border-color'],
            actions: ['onchange'],
            rules: ['ruleRequired', 'ruleErrorMessage']
        }
    };

    /**
     * Show/hide property fields based on component type
     */
    function showRelevantProperties(componentType) {
        const definitions = componentPropertyDefinitions[componentType] || {};
        
        // Hide all property fields first
        $('[id^="prop-"]').closest('div').hide();
        
        // Show only relevant fields for each tab
        Object.keys(definitions).forEach(tab => {
            const fields = definitions[tab];
            fields.forEach(fieldId => {
                $(`#prop-${fieldId}`).closest('div').show();
            });
        });
        
        console.log('=== Showing properties for:', componentType, definitions);
    }

    /**
     * Fill component properties based on type
     */
    function fillComponentProperties($component, componentType) {
        // Show only relevant properties for this component type
        showRelevantProperties(componentType);
        const componentId = $component.attr('id');
        
        // Get saved properties from formData
        const componentData = formData.components.find(comp => comp.id === componentId);
        const savedProperties = componentData ? componentData.properties : {};
        
        console.log('=== Loading properties for:', componentId, savedProperties);
        
        // Fill all property fields with saved values or defaults
        $('#prop-label').val(savedProperties.label || '');
        $('#prop-text').val(savedProperties.text || '');
        $('#prop-name').val(savedProperties.name || '');
        $('#prop-placeholder').val(savedProperties.placeholder || '');
        $('#prop-description').val(savedProperties.description || '');
        $('#prop-required').prop('checked', !!savedProperties.required);
        $('#prop-disabled').prop('checked', !!savedProperties.disabled);
        $('#prop-hidden').prop('checked', !!savedProperties.hidden);
        
        // Style properties
        $('#prop-width').val(savedProperties.width || 'full');
        $('#prop-margin-top').val(savedProperties.marginTop || 0);
        $('#prop-margin-bottom').val(savedProperties.marginBottom || 0);
        $('#prop-padding-x').val(savedProperties.paddingX || 0);
        $('#prop-padding-y').val(savedProperties.paddingY || 0);
        $('#prop-bg-color').val(rgbToHex(savedProperties.bgColor) || '#ffffff');
        $('#prop-text-color').val(rgbToHex(savedProperties.textColor) || '#000000');
        $('#prop-border-style').val(savedProperties.borderStyle || 'none');
        $('#prop-border-width').val(savedProperties.borderWidth || 0);
        $('#prop-border-color').val(rgbToHex(savedProperties.borderColor) || '#000000');
        
        // Component-specific properties
        $('#prop-level').val(savedProperties.level || 'h2');
        $('#prop-rows').val(savedProperties.rows || 4);
        $('#prop-value').val(savedProperties.value || '');
        $('#prop-options').val(savedProperties.options || '');
        $('#prop-button-type').val(savedProperties.type || 'button');
    }
    
    /**
     * Apply component properties
     */
    function applyComponentProperties() {
        if (!selectedComponent) return;
        
        const componentId = selectedComponent.attr('id');
        const componentType = selectedComponent.data('type');
        const label = $('#prop-label').val();
        const placeholder = $('#prop-placeholder').val();
        const name = $('#prop-name').val();
        const required = $('#prop-required').is(':checked');
        const disabled = $('#prop-disabled').is(':checked');
        const hidden = $('#prop-hidden').is(':checked');
        
        // Style tab
        const width = $('#prop-width').val();
        const marginTop = parseInt($('#prop-margin-top').val() || 0, 10);
        const marginBottom = parseInt($('#prop-margin-bottom').val() || 0, 10);
        const paddingX = parseInt($('#prop-padding-x').val() || 0, 10);
        const paddingY = parseInt($('#prop-padding-y').val() || 0, 10);
        const bgColor = $('#prop-bg-color').val();
        const textColor = $('#prop-text-color').val();
        const borderStyle = $('#prop-border-style').val();
        const borderWidth = parseInt($('#prop-border-width').val() || 0, 10);
        const borderColor = $('#prop-border-color').val();
        
        switch(componentType) {
            case 'input':
                selectedComponent.find('.component-label').text(label);
                selectedComponent.find('.component-input')
                    .attr('placeholder', placeholder)
                    .attr('name', name || '')
                    .prop('required', !!required)
                    .prop('disabled', !!disabled)
                    .toggleClass('hidden', !!hidden);
                break;
            case 'button':
                selectedComponent.find('.component-button')
                    .text(label)
                    .prop('disabled', !!disabled)
                    .toggleClass('hidden', !!hidden);
                break;
            case 'header':
                selectedComponent.find('.component-header')
                    .text(label)
                    .toggleClass('hidden', !!hidden);
                break;
            case 'textarea':
                selectedComponent.find('.component-label').text(label);
                selectedComponent.find('.component-textarea')
                    .attr('placeholder', placeholder)
                    .attr('name', name || '')
                    .prop('required', !!required)
                    .prop('disabled', !!disabled)
                    .toggleClass('hidden', !!hidden);
                break;
            case 'dropdown':
                selectedComponent.find('.component-label').text(label);
                const options = $('#prop-options').val().split('\n').filter(opt => opt.trim());
                const select = selectedComponent.find('.component-select');
                select.empty();
                select.append('<option value="">Select an option...</option>');
                options.forEach(option => {
                    select.append(`<option value="${option.trim()}">${option.trim()}</option>`);
                });
                select.attr('name', name || '')
                    .prop('required', !!required)
                    .prop('disabled', !!disabled)
                    .toggleClass('hidden', !!hidden);
                break;
            case 'checkbox':
                selectedComponent.find('.component-label').text(label);
                selectedComponent.find('.component-checkbox')
                    .attr('name', name || '')
                    .attr('value', $('#prop-value').val())
                    .prop('required', !!required)
                    .prop('disabled', !!disabled)
                    .toggleClass('hidden', !!hidden);
                break;
            case 'radiogroup':
                selectedComponent.find('.component-label').text(label);
                const radioOptions = $('#prop-options').val().split('\n').filter(opt => opt.trim());
                const radioContainer = selectedComponent.find('.component-radios');
                radioContainer.empty();
                radioOptions.forEach((option, index) => {
                    const radioId = `${componentId}_radio_${index}`;
                    radioContainer.append(`
                        <div class="flex items-center">
                            <input type="radio" id="${radioId}" name="${name || 'radio_group'}" value="${option.trim()}" class="h-4 w-4 text-blue-600 border-gray-300">
                            <label for="${radioId}" class="ml-2 text-sm text-gray-700">${option.trim()}</label>
                        </div>
                    `);
                });
                selectedComponent.prop('required', !!required)
                    .prop('disabled', !!disabled)
                    .toggleClass('hidden', !!hidden);
                break;
        }
        
        // Apply style to only this component's card
        const $card = selectedComponent.find('> .component-card');
        if ($card.length) {
            // width presets via Tailwind utility classes
            $card.removeClass('w-full w-1/2 w-1/3 w-1/4');
            if (width === 'full') $card.addClass('w-full');
            if (width === 'half') $card.addClass('w-1/2');
            if (width === 'third') $card.addClass('w-1/3');
            if (width === 'quarter') $card.addClass('w-1/4');
            
            $card.css({
                marginTop: `${marginTop}px`,
                marginBottom: `${marginBottom}px`,
                paddingLeft: `${paddingX}px`,
                paddingRight: `${paddingX}px`,
                paddingTop: `${paddingY}px`,
                paddingBottom: `${paddingY}px`,
                backgroundColor: bgColor || '',
                color: textColor || '',
                borderStyle: borderStyle || 'none',
                borderWidth: `${borderWidth || 0}px`,
                borderColor: borderColor || ''
            });
        }
        
        // Update form data with current properties
        const componentIndex = formData.components.findIndex(comp => comp.id === componentId);
        
        if (componentIndex !== -1) {
            // Create properties object based on component type
            let properties = {
                width: width,
                marginTop: marginTop,
                marginBottom: marginBottom,
                paddingX: paddingX,
                paddingY: paddingY,
                bgColor: bgColor,
                textColor: textColor,
                borderStyle: borderStyle,
                borderWidth: borderWidth,
                borderColor: borderColor,
                hidden: hidden
            };
            
            // Add component-specific properties
            switch(componentType) {
                case 'input':
                    properties = {
                        ...properties,
                        label: label,
                        name: name,
                        placeholder: placeholder,
                        description: $('#prop-description').val(),
                        required: required,
                        disabled: disabled
                    };
                    break;
                case 'button':
                    properties = {
                        ...properties,
                        label: label,
                        text: $('#prop-text').val(),
                        type: $('#prop-button-type').val(),
                        disabled: disabled
                    };
                    break;
                case 'header':
                    properties = {
                        ...properties,
                        text: $('#prop-text').val(),
                        level: $('#prop-level').val()
                    };
                    break;
                case 'textarea':
                    properties = {
                        ...properties,
                        label: label,
                        name: name,
                        placeholder: placeholder,
                        description: $('#prop-description').val(),
                        rows: parseInt($('#prop-rows').val() || 4, 10),
                        required: required,
                        disabled: disabled
                    };
                    break;
                case 'dropdown':
                    properties = {
                        ...properties,
                        label: label,
                        name: name,
                        options: $('#prop-options').val(),
                        required: required,
                        disabled: disabled
                    };
                    break;
                case 'checkbox':
                    properties = {
                        ...properties,
                        label: label,
                        name: name,
                        value: $('#prop-value').val(),
                        required: required,
                        disabled: disabled
                    };
                    break;
                case 'radiogroup':
                    properties = {
                        ...properties,
                        label: label,
                        name: name,
                        options: $('#prop-options').val(),
                        required: required,
                        disabled: disabled
                    };
                    break;
            }
            
            // Update formData
            formData.components[componentIndex].properties = properties;
            
            console.log('=== Properties saved to formData for:', componentId, componentType, properties);
        }
        
        // Show success message
        showNotification('Properties applied successfully', 'success');
    }
    
    /**
     * Switch property tab
     */
    function switchPropertyTab(tab) {
        $('.property-tab').removeClass('active border-blue-500 text-blue-600').addClass('border-transparent text-gray-500');
        $(`.property-tab[data-tab="${tab}"]`).addClass('active border-blue-500 text-blue-600').removeClass('border-transparent text-gray-500');
        
        $('.tab-content').hide();
        $(`#tab-${tab}`).show();
    }
    
    /**
     * Delete component
     */
    function deleteComponent(componentId) {
        if (confirm('Are you sure you want to delete this component?')) {
            $(`#${componentId}`).remove();
            
            // Remove from form data
            formData.components = formData.components.filter(comp => comp.id !== componentId);
            
            updateComponentCount();
            
            // Hide properties if this component was selected
            if (selectedComponent && selectedComponent.attr('id') === componentId) {
                $('#component-properties').hide();
                $('#no-selection').show();
                selectedComponent = null;
            }
            
            // Show empty state if no components
            if (formData.components.length === 0) {
                showEmptyState();
            }
        }
    }
    
    /**
     * Update component count
     */
    function updateComponentCount() {
        $('#component-count').text(`Components: ${formData.components.length}`);
    }
    
    /**
     * Hide empty state
     */
    function hideEmptyState() {
        $('#empty-state').hide();
        $('#form-components').show();
    }
    
    /**
     * Show empty state
     */
    function showEmptyState() {
        $('#form-components').hide();
        $('#empty-state').show();
    }
    
    /**
     * Save form
     */
    function saveForm() {
        updateFormData();
        
        if (!formData.name) {
            showSaveModal();
            return;
        }
        
        saveFormToServer();
    }
    
    /**
     * Show save modal
     */
    function showSaveModal() {
        $('#save-form-modal').removeClass('hidden');
        $('#form-name-input').val(formData.name || '');
        $('#form-description-input').val(formData.description || '');
    }
    
    /**
     * Hide save modal
     */
    function hideSaveModal() {
        $('#save-form-modal').addClass('hidden');
    }
    
    /**
     * Save form with name
     */
    function saveFormWithName() {
        const name = $('#form-name-input').val().trim();
        const description = $('#form-description-input').val().trim();
        
        if (!name) {
            showNotification('Please enter a form name', 'error');
            return;
        }
        
        formData.name = name;
        formData.description = description;
        
        hideSaveModal();
        saveFormToServer();
    }
    
    /**
     * Save form to server
     */
    function saveFormToServer() {
        updateFormData();
        
        $.ajax({
            url: '/api/sandbox/form-creator/save',
            method: 'POST',
            data: {
                filename: formData.name.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.json',
                name: formData.name,
                description: formData.description,
                data: JSON.stringify(formData)
            },
            success: function(response) {
                showNotification('Form saved successfully!', 'success');
                $('#form-status').text('Saved');
                loadFormsList(); // Refresh forms list
            },
            error: function(xhr) {
                showNotification('Error saving form: ' + xhr.responseJSON.message, 'error');
            }
        });
    }
    
    /**
     * Export form
     */
    function exportForm() {
        updateFormData();
        
        const dataStr = JSON.stringify(formData, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        
        const exportFileDefaultName = (formData.name || 'form') + '.json';
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('Form exported successfully!', 'success');
    }
    
    /**
     * Import form
     */
    function importForm() {
        $('#import-file-input').click();
    }
    
    /**
     * Handle import file
     */
    function handleImportFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const importedData = JSON.parse(e.target.result);
                loadFormData(importedData);
                showNotification('Form imported successfully!', 'success');
            } catch (error) {
                showNotification('Error importing form: Invalid JSON file', 'error');
            }
        };
        reader.readAsText(file);
        
        // Reset file input
        event.target.value = '';
    }
    
    /**
     * Load form data
     */
    function loadFormData(data) {
        formData = data;
        
        // Clear current form
        $('#form-components').empty();
        componentCounter = 0;
        
        // Recreate components
        if (data.components && data.components.length > 0) {
            data.components.forEach(component => {
                addComponentFromData(component);
            });
            hideEmptyState();
        } else {
            showEmptyState();
        }
        
        updateComponentCount();
        $('#form-status').text('Loaded');
    }
    
    /**
     * Add component from saved data
     */
    function addComponentFromData(componentData) {
        const componentId = componentData.id;
        componentCounter = Math.max(componentCounter, parseInt(componentId.replace('comp_', '')));
        
        let componentHtml = '';
        
        switch(componentData.type) {
            case 'input':
                componentHtml = createInputComponent(componentId);
                break;
            case 'button':
                componentHtml = createButtonComponent(componentId);
                break;
            case 'header':
                componentHtml = createHeaderComponent(componentId);
                break;
            case 'textarea':
                componentHtml = createTextareaComponent(componentId);
                break;
            case 'dropdown':
                componentHtml = createDropdownComponent(componentId);
                break;
            case 'checkbox':
                componentHtml = createCheckboxComponent(componentId);
                break;
            case 'radiogroup':
                componentHtml = createRadioGroupComponent(componentId);
                break;
            default:
                componentHtml = createGenericComponent(componentId, componentData.type);
        }
        
        $('#form-components').append(componentHtml);
        
        // Apply saved properties
        const $component = $('#' + componentId);
        applyComponentDataToElement($component, componentData);
    }
    
    /**
     * Apply component data to element
     */
    function applyComponentDataToElement($component, componentData) {
        const properties = componentData.properties || {};
        
        switch(componentData.type) {
            case 'input':
                if (properties.label) $component.find('.component-label').text(properties.label);
                if (properties.placeholder) $component.find('.component-input').attr('placeholder', properties.placeholder);
                if (properties.name !== undefined) $component.find('.component-input').attr('name', properties.name);
                if (properties.required !== undefined) $component.find('.component-input').prop('required', !!properties.required);
                if (properties.disabled !== undefined) $component.find('.component-input').prop('disabled', !!properties.disabled);
                if (properties.hidden !== undefined) $component.toggleClass('hidden', !!properties.hidden);
                break;
            case 'button':
                if (properties.text) $component.find('.component-button').text(properties.text);
                if (properties.disabled !== undefined) $component.find('.component-button').prop('disabled', !!properties.disabled);
                if (properties.hidden !== undefined) $component.toggleClass('hidden', !!properties.hidden);
                break;
            case 'header':
                if (properties.text) $component.find('.component-header').text(properties.text);
                if (properties.hidden !== undefined) $component.toggleClass('hidden', !!properties.hidden);
                break;
            case 'textarea':
                if (properties.label) $component.find('.component-label').text(properties.label);
                if (properties.placeholder) $component.find('.component-textarea').attr('placeholder', properties.placeholder);
                if (properties.name !== undefined) $component.find('.component-textarea').attr('name', properties.name);
                if (properties.required !== undefined) $component.find('.component-textarea').prop('required', !!properties.required);
                if (properties.disabled !== undefined) $component.find('.component-textarea').prop('disabled', !!properties.disabled);
                if (properties.hidden !== undefined) $component.toggleClass('hidden', !!properties.hidden);
                break;
        }

        // Restore style properties per component
        const $card = $component.find('> .component-card');
        if ($card.length) {
            const width = properties.width;
            const marginTop = properties.marginTop;
            const marginBottom = properties.marginBottom;
            const paddingX = properties.paddingX;
            const paddingY = properties.paddingY;
            const bgColor = properties.bgColor;
            const textColor = properties.textColor;
            const borderStyle = properties.borderStyle;
            const borderWidth = properties.borderWidth;
            const borderColor = properties.borderColor;

            $card.removeClass('w-full w-1/2 w-1/3 w-1/4');
            if (width === 'full') $card.addClass('w-full');
            if (width === 'half') $card.addClass('w-1/2');
            if (width === 'third') $card.addClass('w-1/3');
            if (width === 'quarter') $card.addClass('w-1/4');

            $card.css({
                marginTop: marginTop !== undefined ? `${marginTop}px` : '',
                marginBottom: marginBottom !== undefined ? `${marginBottom}px` : '',
                paddingLeft: paddingX !== undefined ? `${paddingX}px` : '',
                paddingRight: paddingX !== undefined ? `${paddingX}px` : '',
                paddingTop: paddingY !== undefined ? `${paddingY}px` : '',
                paddingBottom: paddingY !== undefined ? `${paddingY}px` : '',
                backgroundColor: bgColor || '',
                color: textColor || '',
                borderStyle: borderStyle || '',
                borderWidth: borderWidth !== undefined ? `${borderWidth}px` : '',
                borderColor: borderColor || ''
            });
        }
    }
    
    /**
     * Update form data
     */
    function updateFormData() {
        formData.components = [];
        
        $('#form-components .component-wrapper').each(function() {
            const $component = $(this);
            const componentId = $component.attr('id');
            const componentType = $component.data('type');
            const properties = extractComponentProperties($component, componentType);
            
            formData.components.push({
                id: componentId,
                type: componentType,
                properties: properties
            });
        });
    }
    
    /**
     * Extract component properties
     */
    function extractComponentProperties($component, componentType) {
        const properties = {};
        
        switch(componentType) {
            case 'input':
                properties.label = $component.find('.component-label').text();
                properties.placeholder = $component.find('.component-input').attr('placeholder');
                properties.name = $component.find('.component-input').attr('name') || '';
                properties.required = $component.find('.component-input').prop('required') || false;
                properties.disabled = $component.find('.component-input').prop('disabled') || false;
                properties.hidden = $component.hasClass('hidden');
                break;
            case 'button':
                properties.text = $component.find('.component-button').text();
                properties.type = $component.find('.component-button').attr('type') || 'button';
                properties.disabled = $component.find('.component-button').prop('disabled') || false;
                properties.hidden = $component.hasClass('hidden');
                break;
            case 'header':
                properties.text = $component.find('.component-header').text();
                properties.level = $component.find('.component-header').prop('tagName').toLowerCase();
                properties.hidden = $component.hasClass('hidden');
                break;
            case 'textarea':
                properties.label = $component.find('.component-label').text();
                properties.placeholder = $component.find('.component-textarea').attr('placeholder');
                properties.rows = $component.find('.component-textarea').attr('rows') || 4;
                properties.name = $component.find('.component-textarea').attr('name') || '';
                properties.required = $component.find('.component-textarea').prop('required') || false;
                properties.disabled = $component.find('.component-textarea').prop('disabled') || false;
                properties.hidden = $component.hasClass('hidden');
                break;
        }
        // Read style set on component-card
        const $card = $component.find('> .component-card');
        if ($card.length) {
            if ($card.hasClass('w-full')) properties.width = 'full';
            if ($card.hasClass('w-1/2')) properties.width = 'half';
            if ($card.hasClass('w-1/3')) properties.width = 'third';
            if ($card.hasClass('w-1/4')) properties.width = 'quarter';
            properties.marginTop = parseInt(($card.css('marginTop') || '0').replace('px','')) || 0;
            properties.marginBottom = parseInt(($card.css('marginBottom') || '0').replace('px','')) || 0;
            properties.paddingX = parseInt(($card.css('paddingLeft') || '0').replace('px','')) || 0;
            properties.paddingY = parseInt(($card.css('paddingTop') || '0').replace('px','')) || 0;
            properties.bgColor = $card.css('backgroundColor');
            properties.textColor = $card.css('color');
            properties.borderStyle = $card.css('borderStyle');
            properties.borderWidth = parseInt(($card.css('borderWidth') || '0').replace('px','')) || 0;
            properties.borderColor = $card.css('borderColor');
        }

        return properties;
    }
    
    /**
     * Clear form
     */
    function clearForm() {
        if (confirm('Are you sure you want to clear all components?')) {
            $('#form-components').empty();
            formData = { name: '', description: '', components: [], settings: {} };
            componentCounter = 0;
            selectedComponent = null;
            
            $('#component-properties').hide();
            $('#no-selection').show();
            showEmptyState();
            updateComponentCount();
            
            showNotification('Form cleared', 'info');
        }
    }
    
    /**
     * Show form manager
     */
    function showFormManager() {
        $('#form-manager-modal').removeClass('hidden');
        loadFormsList();
    }
    
    /**
     * Hide form manager
     */
    function hideFormManager() {
        $('#form-manager-modal').addClass('hidden');
    }
    
    /**
     * Load forms list
     */
    function loadFormsList() {
        $('#forms-loading').show();
        $('#forms-empty').hide();
        
        $.ajax({
            url: '/api/sandbox/form-creator/list',
            method: 'GET',
            success: function(response) {
                displayFormsList(response.forms || []);
            },
            error: function(xhr) {
                console.error('Error loading forms:', xhr);
                displayFormsList([]);
            }
        });
    }
    
    /**
     * Display forms list
     */
    function displayFormsList(forms) {
        $('#forms-loading').hide();
        
        const $formsList = $('#forms-list');
        
        if (forms.length === 0) {
            $('#forms-empty').show();
            return;
        }
        
        // Clear existing items (keep loading and empty states)
        $formsList.find('.form-item').remove();
        
        forms.forEach(form => {
            const $formItem = createFormListItem(form);
            $formsList.append($formItem);
        });
    }
    
    /**
     * Create form list item
     */
    function createFormListItem(form) {
        const $template = $('#form-item-template .form-item').clone();
        
        $template.data('filename', form.filename);
        $template.find('.form-name').text(form.name || form.filename);
        $template.find('.form-description').text(form.description || 'No description');
        $template.find('.form-created').text(formatDate(form.created_at));
        $template.find('.form-modified').text(formatDate(form.modified_at));
        $template.find('.form-components').text(form.component_count || 0);
        
        return $template;
    }
    
    /**
     * Format date
     */
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
    
    /**
     * Load form
     */
    function loadForm(filename) {
        $.ajax({
            url: '/api/sandbox/form-creator/load/' + filename,
            method: 'GET',
            success: function(response) {
                const formData = JSON.parse(response.data);
                loadFormData(formData);
                hideFormManager();
                showNotification('Form loaded successfully!', 'success');
            },
            error: function(xhr) {
                showNotification('Error loading form: ' + xhr.responseJSON.message, 'error');
            }
        });
    }
    
    /**
     * Delete form
     */
    function deleteForm(filename) {
        if (confirm('Are you sure you want to delete this form?')) {
            $.ajax({
                url: '/api/sandbox/form-creator/delete/' + filename,
                method: 'DELETE',
                success: function(response) {
                    showNotification('Form deleted successfully!', 'success');
                    loadFormsList();
                },
                error: function(xhr) {
                    showNotification('Error deleting form: ' + xhr.responseJSON.message, 'error');
                }
            });
        }
    }
    
    /**
     * Export saved form
     */
    function exportSavedForm(filename) {
        $.ajax({
            url: '/api/sandbox/form-creator/load/' + filename,
            method: 'GET',
            success: function(response) {
                const dataStr = response.data;
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', filename);
                linkElement.click();
                
                showNotification('Form exported successfully!', 'success');
            },
            error: function(xhr) {
                showNotification('Error exporting form: ' + xhr.responseJSON.message, 'error');
            }
        });
    }
    
    /**
     * Filter forms
     */
    function filterForms() {
        const query = $('#search-forms').val().toLowerCase();
        
        $('.form-item').each(function() {
            const $item = $(this);
            const name = $item.find('.form-name').text().toLowerCase();
            const description = $item.find('.form-description').text().toLowerCase();
            
            if (name.includes(query) || description.includes(query)) {
                $item.show();
            } else {
                $item.hide();
            }
        });
    }
    
    /**
     * New form
     */
    function newForm() {
        if (formData.components.length > 0 && !confirm('Are you sure you want to create a new form? Unsaved changes will be lost.')) {
            return;
        }
        
        clearForm();
        hideFormManager();
        showNotification('New form created', 'info');
    }
    
    /**
     * Show notification
     */
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };
        
        const $notification = $(`
            <div class="fixed top-4 right-4 ${colors[type]} text-white px-4 py-2 rounded-md shadow-lg z-50 notification">
                ${message}
            </div>
        `);
        
        $('body').append($notification);
        
        setTimeout(() => {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    /**
     * Update component order
     */
    function updateComponentOrder() {
        // Update component IDs based on new order
        $('#form-components .component-wrapper').each(function(index) {
            const newId = 'comp_' + (index + 1);
            $(this).attr('id', newId);
            
            // Update any internal references
            $(this).find('[id*="comp_"]').each(function() {
                const currentId = $(this).attr('id');
                const newInternalId = currentId.replace(/comp_\d+/, newId);
                $(this).attr('id', newInternalId);
            });
        });
        
        // Update form data
        updateFormData();
    }
    
    /**
     * Update selected component
     */
    function updateSelectedComponent() {
        // Real-time property updates
        applyComponentProperties();
    }
    
    /**
     * Reset component properties
     */
    function resetComponentProperties() {
        if (!selectedComponent) return;
        
        const componentType = selectedComponent.data('type');
        const defaults = getDefaultProperties(componentType);
        
        // Reset form fields
        $('#prop-label').val(defaults.label || '');
        $('#prop-name').val(defaults.name || '');
        $('#prop-placeholder').val(defaults.placeholder || '');
        $('#prop-description').val('');
        $('#prop-required').prop('checked', false);
        $('#prop-disabled').prop('checked', false);
        $('#prop-hidden').prop('checked', false);
    }
    
    /**
     * Switch right panel tab
     */
    function switchRightPanelTab(tab) {
        $('.right-panel-tab').removeClass('active border-blue-500 text-blue-600').addClass('border-transparent text-gray-500');
        $(`.right-panel-tab[data-tab="${tab}"]`).addClass('active border-blue-500 text-blue-600').removeClass('border-transparent text-gray-500');
        
        $('.right-tab-content').hide();
        $(`#right-tab-${tab}`).show();
        
        // Load content based on tab
        if (tab === 'tree') {
            updateFormTree();
        } else if (tab === 'forms') {
            loadQuickFormsList();
        } else if (tab === 'settings') {
            loadFormSettings();
        }
    }
    
    /**
     * Update form tree
     */
    function updateFormTree() {
        const $treeComponents = $('#tree-components');
        const $treeEmpty = $('#tree-empty');
        const $componentCount = $('#tree-component-count');
        
        $treeComponents.empty();
        
        if (formData.components.length === 0) {
            $treeEmpty.show();
            $componentCount.text('0 components');
            return;
        }
        
        $treeEmpty.hide();
        $componentCount.text(`${formData.components.length} component${formData.components.length > 1 ? 's' : ''}`);
        
        formData.components.forEach((component, index) => {
            const $treeItem = createTreeItem(component, index);
            $treeComponents.append($treeItem);
        });
    }
    
    /**
     * Create tree item
     */
    function createTreeItem(component, index) {
        const icons = {
            'input': 'fas fa-edit',
            'button': 'fas fa-hand-pointer',
            'header': 'fas fa-heading',
            'textarea': 'fas fa-align-left',
            'dropdown': 'fas fa-chevron-down',
            'checkbox': 'fas fa-check-square',
            'radiogroup': 'fas fa-dot-circle'
        };
        
        const icon = icons[component.type] || 'fas fa-puzzle-piece';
        const label = component.properties?.label || component.type;
        
        return $(`
            <div class="tree-item cursor-pointer" data-component-id="${component.id}" data-level="1">
                <div class="flex items-center py-1 px-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                    <i class="${icon} mr-2 text-gray-500"></i>
                    <span class="flex-1">${label}</span>
                    <span class="text-xs text-gray-400">${component.type}</span>
                    <button class="ml-2 text-xs text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100" onclick="deleteComponent('${component.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
    }
    
    /**
     * Select tree item
     */
    function selectTreeItem() {
        const componentId = $(this).data('component-id');
        if (componentId) {
            const $component = $('#' + componentId);
            if ($component.length) {
                selectComponent($component);
                switchRightPanelTab('components');
            }
        }
    }
    
    /**
     * Expand all tree nodes
     */
    function expandAllTreeNodes() {
        $('.tree-item').show();
        $('.tree-node-toggle i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
    }
    
    /**
     * Toggle tree node
     */
    function toggleTreeNode() {
        // Implementation for nested tree nodes if needed
    }
    
    /**
     * Load form settings
     */
    function loadFormSettings() {
        $('#settings-form-title').val(formData.name || '');
        $('#settings-form-description').val(formData.description || '');
        $('#settings-submit-text').val(formData.settings?.submitText || 'Submit');
        $('#settings-success-message').val(formData.settings?.successMessage || 'Form submitted successfully!');
        $('#settings-layout-style').val(formData.settings?.layoutStyle || 'vertical');
        $('#settings-theme').val(formData.settings?.theme || 'default');
        $('#settings-error-position').val(formData.settings?.errorPosition || 'below');
        
        // Checkboxes
        $('#settings-show-progress').prop('checked', formData.settings?.showProgress || false);
        $('#settings-show-labels').prop('checked', formData.settings?.showLabels !== false);
        $('#settings-required-asterisk').prop('checked', formData.settings?.requiredAsterisk !== false);
        $('#settings-validate-realtime').prop('checked', formData.settings?.validateRealtime || false);
        $('#settings-show-errors').prop('checked', formData.settings?.showErrors !== false);
    }
    
    /**
     * Update form settings
     */
    function updateFormSettings() {
        if (!formData.settings) {
            formData.settings = {};
        }
        
        formData.name = $('#settings-form-title').val();
        formData.description = $('#settings-form-description').val();
        formData.settings.submitText = $('#settings-submit-text').val();
        formData.settings.successMessage = $('#settings-success-message').val();
        formData.settings.layoutStyle = $('#settings-layout-style').val();
        formData.settings.theme = $('#settings-theme').val();
        formData.settings.errorPosition = $('#settings-error-position').val();
        
        // Checkboxes
        formData.settings.showProgress = $('#settings-show-progress').is(':checked');
        formData.settings.showLabels = $('#settings-show-labels').is(':checked');
        formData.settings.requiredAsterisk = $('#settings-required-asterisk').is(':checked');
        formData.settings.validateRealtime = $('#settings-validate-realtime').is(':checked');
        formData.settings.showErrors = $('#settings-show-errors').is(':checked');
    }
    
    /**
     * Apply form settings
     */
    function applyFormSettings() {
        updateFormSettings();
        applyThemeSettings();
        showNotification('Settings applied successfully!', 'success');
    }
    
    /**
     * Reset form settings
     */
    function resetFormSettings() {
        formData.settings = {};
        loadFormSettings();
        applyThemeSettings();
        showNotification('Settings reset to defaults', 'info');
    }
    
    /**
     * Apply theme settings to form
     */
    function applyThemeSettings() {
        const theme = formData.settings?.theme || 'default';
        const $formCanvas = $('#form-canvas');
        
        // Remove existing theme classes
        $formCanvas.removeClass('theme-default theme-minimal theme-modern theme-classic');
        
        // Add new theme class
        $formCanvas.addClass(`theme-${theme}`);
        
        // Apply layout style
        const layoutStyle = formData.settings?.layoutStyle || 'vertical';
        $formCanvas.removeClass('layout-vertical layout-horizontal layout-inline').addClass(`layout-${layoutStyle}`);
    }
    
    /**
     * Load quick forms list
     */
    function loadQuickFormsList() {
        const $quickFormsList = $('#quick-forms-list');
        const $quickFormsEmpty = $('#quick-forms-empty');
        
        $.ajax({
            url: '/api/sandbox/form-creator/list',
            method: 'GET',
            success: function(response) {
                displayQuickFormsList(response.forms || []);
            },
            error: function(xhr) {
                console.error('Error loading forms:', xhr);
                displayQuickFormsList([]);
            }
        });
    }
    
    /**
     * Display quick forms list
     */
    function displayQuickFormsList(forms) {
        const $quickFormsList = $('#quick-forms-list');
        const $quickFormsEmpty = $('#quick-forms-empty');
        
        $quickFormsList.empty();
        
        if (forms.length === 0) {
            $quickFormsEmpty.show();
            return;
        }
        
        $quickFormsEmpty.hide();
        
        forms.slice(0, 10).forEach(form => { // Show only first 10 forms
            const $formItem = createQuickFormItem(form);
            $quickFormsList.append($formItem);
        });
    }
    
    /**
     * Create quick form item
     */
    function createQuickFormItem(form) {
        const date = new Date(form.modified_at).toLocaleDateString();
        return $(`
            <div class="quick-form-item bg-gray-50 p-3 rounded-lg hover:bg-gray-100 cursor-pointer" data-filename="${form.filename}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h5 class="font-medium text-sm text-gray-900 truncate">${form.name}</h5>
                        <p class="text-xs text-gray-500 mt-1 truncate">${form.description || 'No description'}</p>
                        <div class="flex items-center mt-2 text-xs text-gray-400">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>${date}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-puzzle-piece mr-1"></i>
                            <span>${form.component_count} components</span>
                        </div>
                    </div>
                    <div class="flex ml-2">
                        <button class="quick-load-form text-blue-600 hover:text-blue-800 p-1" data-filename="${form.filename}">
                            <i class="fas fa-folder-open text-xs"></i>
                        </button>
                        <button class="quick-delete-form text-red-600 hover:text-red-800 p-1 ml-1" data-filename="${form.filename}">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
    }
    
    /**
     * Quick filter forms
     */
    function quickFilterForms() {
        const query = $('#quick-search-forms').val().toLowerCase();
        
        $('.quick-form-item').each(function() {
            const $item = $(this);
            const name = $item.find('h5').text().toLowerCase();
            const description = $item.find('p').text().toLowerCase();
            
            if (name.includes(query) || description.includes(query)) {
                $item.show();
            } else {
                $item.hide();
            }
        });
    }
    
    // Update component count when components change
    const originalAddComponentToCanvas = addComponentToCanvas;
    addComponentToCanvas = function(componentType) {
        originalAddComponentToCanvas(componentType);
        updateFormTree();
    };
    
    // Update tree when component is deleted  
    const originalDeleteComponent = deleteComponent;
    deleteComponent = function(componentId) {
        originalDeleteComponent(componentId);
        updateFormTree();
    };
    
    // Add event handlers for quick forms
    $(document).on('click', '.quick-load-form', function(e) {
        e.stopPropagation();
        const filename = $(this).data('filename');
        loadForm(filename);
    });
    
    $(document).on('click', '.quick-delete-form', function(e) {
        e.stopPropagation();
        const filename = $(this).data('filename');
        deleteForm(filename);
    });
    
    $(document).on('click', '.quick-form-item', function(e) {
        if (!$(e.target).closest('button').length) {
            const filename = $(this).data('filename');
            loadForm(filename);
        }
    });
    
    // Global functions for onclick handlers
    window.editComponent = function(componentId) {
        const $component = $('#' + componentId);
        selectComponent($component);
    };
    
    window.deleteComponent = function(componentId) {
        deleteComponent(componentId);
    };
});
</script>