<div class="space-y-6">
    {{-- 헤더 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">👀 폼 미리보기</h1>
                <p class="text-gray-600 mt-1">{{ $form->title }}</p>
                @if($form->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $form->description }}</p>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('sandbox.form-publisher.editor', ['edit' => $form->id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    ✏️ 편집
                </a>
                <a href="{{ route('sandbox.form-publisher.list') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-md transition-colors duration-200">
                    📋 목록
                </a>
            </div>
        </div>
    </div>

    {{-- 폼 미리보기 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @if(session()->has('form-submitted'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('form-submitted') }}
                </div>
            </div>
        @endif

        @if($form->form_fields && count($form->form_fields) > 0)
            <form wire:submit.prevent="submitForm" class="space-y-6">
                @foreach($form->form_fields as $field)
                    <div class="form-field">
                        @php
                            $fieldId = $field['id'] ?? $field['name'] ?? 'field_' . $loop->index;
                            $fieldType = $field['type'] ?? 'text';
                            $fieldLabel = $field['label'] ?? $field['name'] ?? 'Field ' . ($loop->index + 1);
                            $fieldPlaceholder = $field['placeholder'] ?? '';
                            $fieldRequired = $field['required'] ?? false;
                            $fieldOptions = $field['options'] ?? [];
                        @endphp

                        @if($fieldType === 'text' || $fieldType === 'email' || $fieldType === 'number' || $fieldType === 'url' || $fieldType === 'password')
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fieldLabel }}
                                @if($fieldRequired) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="{{ $fieldType }}" 
                                   id="{{ $fieldId }}"
                                   wire:model="formData.{{ $fieldId }}"
                                   placeholder="{{ $fieldPlaceholder }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   @if($fieldRequired) required @endif>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @elseif($fieldType === 'textarea')
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fieldLabel }}
                                @if($fieldRequired) <span class="text-red-500">*</span> @endif
                            </label>
                            <textarea id="{{ $fieldId }}"
                                      wire:model="formData.{{ $fieldId }}"
                                      placeholder="{{ $fieldPlaceholder }}"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      @if($fieldRequired) required @endif></textarea>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @elseif($fieldType === 'select')
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fieldLabel }}
                                @if($fieldRequired) <span class="text-red-500">*</span> @endif
                            </label>
                            <select id="{{ $fieldId }}"
                                    wire:model="formData.{{ $fieldId }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    @if($fieldRequired) required @endif>
                                <option value="">선택해주세요</option>
                                @if(is_array($fieldOptions))
                                    @foreach($fieldOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @elseif($fieldType === 'checkbox')
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="{{ $fieldId }}"
                                       wire:model="formData.{{ $fieldId }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       @if($fieldRequired) required @endif>
                                <label for="{{ $fieldId }}" class="ml-2 block text-sm text-gray-700">
                                    {{ $fieldLabel }}
                                    @if($fieldRequired) <span class="text-red-500">*</span> @endif
                                </label>
                            </div>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @elseif($fieldType === 'radio')
                            <fieldset>
                                <legend class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $fieldLabel }}
                                    @if($fieldRequired) <span class="text-red-500">*</span> @endif
                                </legend>
                                <div class="space-y-2">
                                    @if(is_array($fieldOptions))
                                        @foreach($fieldOptions as $option)
                                            <div class="flex items-center">
                                                <input type="radio" 
                                                       id="{{ $fieldId }}_{{ $loop->index }}"
                                                       wire:model="formData.{{ $fieldId }}"
                                                       value="{{ $option }}"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                       @if($fieldRequired) required @endif>
                                                <label for="{{ $fieldId }}_{{ $loop->index }}" class="ml-2 block text-sm text-gray-700">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </fieldset>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @elseif($fieldType === 'file')
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fieldLabel }}
                                @if($fieldRequired) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="file" 
                                   id="{{ $fieldId }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   @if($fieldRequired) required @endif>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @elseif($fieldType === 'date')
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fieldLabel }}
                                @if($fieldRequired) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="date" 
                                   id="{{ $fieldId }}"
                                   wire:model="formData.{{ $fieldId }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   @if($fieldRequired) required @endif>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @else
                            {{-- 기본값: 텍스트 필드로 처리 --}}
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fieldLabel }}
                                @if($fieldRequired) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="text" 
                                   id="{{ $fieldId }}"
                                   wire:model="formData.{{ $fieldId }}"
                                   placeholder="{{ $fieldPlaceholder }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   @if($fieldRequired) required @endif>
                            @error("formData.{$fieldId}")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                @endforeach

                {{-- 제출 버튼 --}}
                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        {{ $form->form_settings['submitText'] ?? '제출하기' }}
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">폼 필드가 없습니다</h3>
                <p class="mt-1 text-gray-500">이 폼에는 아직 필드가 정의되지 않았습니다.</p>
                <div class="mt-6">
                    <a href="{{ route('sandbox.form-publisher.editor', ['edit' => $form->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        ✏️ 편집하기
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- 폼 정보 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">폼 정보</h2>
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">폼 ID</dt>
                <dd class="text-sm text-gray-900">#{{ $form->id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">생성일</dt>
                <dd class="text-sm text-gray-900">{{ $form->created_at->format('Y-m-d H:i') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">수정일</dt>
                <dd class="text-sm text-gray-900">{{ $form->updated_at->format('Y-m-d H:i') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">필드 개수</dt>
                <dd class="text-sm text-gray-900">{{ is_array($form->form_fields) ? count($form->form_fields) : 0 }}개</dd>
            </div>
        </dl>
    </div>
</div>
