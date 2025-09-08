<div class="max-w-6xl mx-auto p-6">
    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $i <= $currentStep ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                            @if ($this->isStepCompleted($i))
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                {{ $i }}
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $i <= $currentStep ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ $this->getStepTitle($i) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $this->getStepDescription($i) }}
                            </p>
                        </div>
                    </div>
                    @if ($i < $totalSteps)
                        <div class="flex-1 mx-4 h-0.5 {{ $i < $currentStep ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <!-- Step Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        
        <!-- Step 1: Template Selection -->
        @if ($currentStep === 1)
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">함수 템플릿 선택</h3>
                    <p class="text-gray-600">생성할 함수의 유형을 선택하세요. 각 템플릿은 특정 용도에 최적화되어 있습니다.</p>
                </div>

                <!-- Category Filter -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <button 
                        wire:click="$set('selectedCategory', '')"
                        class="px-4 py-2 text-sm rounded-lg border {{ empty($selectedCategory) ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}"
                    >
                        전체
                    </button>
                    @foreach ($categories as $catId => $category)
                        <button 
                            wire:click="$set('selectedCategory', '{{ $catId }}')"
                            class="px-4 py-2 text-sm rounded-lg border {{ $selectedCategory === $catId ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}"
                        >
                            {{ $category['name'] }}
                        </button>
                    @endforeach
                </div>

                <!-- Templates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($this->getTemplatesByCategory($selectedCategory) as $templateId => $template)
                        <div 
                            wire:click="selectTemplate('{{ $templateId }}')"
                            class="p-4 border rounded-lg cursor-pointer transition-all {{ $selectedTemplate === $templateId ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-200' : 'bg-white border-gray-200 hover:bg-gray-50 hover:border-gray-300' }}"
                        >
                            <div class="flex items-start space-x-3">
                                <div class="text-2xl">{{ $template['icon'] ?? '📦' }}</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $template['name'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $template['description'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach ($template['tags'] ?? [] as $tag)
                                            <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('selectedTemplate')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>
        @endif

        <!-- Step 2: Basic Information -->
        @if ($currentStep === 2)
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">기본 정보 입력</h3>
                    <p class="text-gray-600">함수의 이름과 설명을 입력하세요.</p>
                </div>

                @if ($template)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-xl">{{ $template['icon'] ?? '📦' }}</span>
                            <div>
                                <h4 class="font-medium text-blue-900">{{ $template['name'] }}</h4>
                                <p class="text-sm text-blue-700">{{ $template['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="functionName" class="block text-sm font-medium text-gray-700 mb-2">
                            함수 이름 <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="functionName"
                            wire:model.blur="functionName"
                            placeholder="예: UserManager"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('functionName')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            영문자로 시작하며, 영문자, 숫자, 언더스코어만 사용 가능합니다.
                        </p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            함수 설명
                        </label>
                        <textarea 
                            id="description"
                            wire:model="description"
                            rows="3"
                            placeholder="함수의 용도와 기능을 간략히 설명하세요..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 3: Parameters -->
        @if ($currentStep === 3)
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">파라미터 설정</h3>
                    <p class="text-gray-600">선택한 템플릿에 필요한 파라미터를 설정하세요.</p>
                </div>

                @if (!empty($templateParameters))
                    <div class="space-y-4">
                        @foreach ($templateParameters as $param)
                            @if ($param['name'] !== 'className')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $param['description'] ?? $param['name'] }}
                                        @if ($param['required'] ?? false)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if ($param['type'] === 'array' && isset($param['options']))
                                        <!-- Multi-select checkboxes -->
                                        <div class="space-y-2 p-3 border border-gray-300 rounded-lg bg-gray-50">
                                            @foreach ($param['options'] as $option)
                                                <label class="flex items-center space-x-2">
                                                    <input 
                                                        type="checkbox" 
                                                        value="{{ $option }}"
                                                        wire:model="parameterValues.{{ $param['name'] }}"
                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                    >
                                                    <span class="text-sm text-gray-700">{{ ucfirst($option) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif (isset($param['options']))
                                        <!-- Select dropdown -->
                                        <select 
                                            wire:model="parameterValues.{{ $param['name'] }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            <option value="">선택하세요...</option>
                                            @foreach ($param['options'] as $option)
                                                <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <!-- Text input -->
                                        <input 
                                            type="text" 
                                            wire:model="parameterValues.{{ $param['name'] }}"
                                            placeholder="{{ $param['placeholder'] ?? '' }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                    @endif

                                    @error('parameters')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-gray-600">이 템플릿은 추가 파라미터가 필요하지 않습니다.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 4: Preview & Create -->
        @if ($currentStep === 4)
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">미리보기 및 생성</h3>
                    <p class="text-gray-600">생성될 함수 코드를 확인하고 함수를 생성하세요.</p>
                </div>

                <!-- Function Info Summary -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 mb-2">함수 정보</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700">함수명:</span> <span class="font-mono">{{ $functionName }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700">템플릿:</span> {{ $template['name'] ?? '' }}
                        </div>
                        @if (!empty($description))
                            <div class="col-span-2">
                                <span class="text-blue-700">설명:</span> {{ $description }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Generated Code Preview -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">생성될 코드</h4>
                    <div class="relative">
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{{ $generatedCode }}</code></pre>
                        <button 
                            onclick="navigator.clipboard.writeText(document.querySelector('pre code').textContent)"
                            class="absolute top-2 right-2 px-2 py-1 text-xs bg-gray-700 text-gray-300 rounded hover:bg-gray-600"
                        >
                            복사
                        </button>
                    </div>
                </div>

                <!-- Creation Result -->
                @if (!empty($creationResult))
                    <div class="p-4 rounded-lg {{ $creationResult['success'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center space-x-2">
                            @if ($creationResult['success'])
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-green-800 font-medium">성공!</span>
                            @else
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-red-800 font-medium">오류!</span>
                            @endif
                        </div>
                        <p class="mt-2 {{ $creationResult['success'] ? 'text-green-700' : 'text-red-700' }}">
                            {{ $creationResult['message'] }}
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between items-center mt-8">
        <div>
            @if ($currentStep > 1)
                <button 
                    wire:click="previousStep"
                    class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    이전
                </button>
            @endif
        </div>

        <div class="flex items-center space-x-3">
            @if ($currentStep < $totalSteps)
                <button 
                    wire:click="nextStep"
                    class="px-6 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if ($currentStep === 1 && empty($selectedTemplate)) disabled @endif
                    @if ($currentStep === 2 && (empty($functionName) || !preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $functionName))) disabled @endif
                >
                    다음
                </button>
            @else
                <button 
                    wire:click="createFunction"
                    wire:loading.attr="disabled"
                    class="px-6 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="createFunction">함수 생성</span>
                    <span wire:loading wire:target="createFunction">생성 중...</span>
                </button>
            @endif

            <button 
                wire:click="resetWizard"
                class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                처음부터
            </button>
        </div>
    </div>
</div>