<?php

namespace App\Livewire\Sandbox;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class BladeCreator extends Component
{
    public $templateType = '';
    public $fileName = '';
    public $pageTitle = '';
    public $includeHeader = true;
    public $includeFooter = true;
    public $includeScripts = true;
    public $generatedCode = '';

    protected $rules = [
        'fileName' => 'required|string|max:255',
        'pageTitle' => 'nullable|string|max:255',
    ];

    public function selectTemplate($type)
    {
        $this->templateType = $type;
        $this->resetValidation();
    }

    public function generateTemplate()
    {
        $this->validate();

        $code = '';

        switch ($this->templateType) {
            case 'basic':
                $code = $this->generateBasicTemplate();
                break;
            case 'component':
                $code = $this->generateComponentTemplate();
                break;
            case 'layout':
                $code = $this->generateLayoutTemplate();
                break;
            case 'form':
                $code = $this->generateFormTemplate();
                break;
            default:
                $code = '템플릿 타입을 선택해주세요.';
        }

        $this->generatedCode = $code;

        session()->flash('message', 'Blade 템플릿이 성공적으로 생성되었습니다!');
    }

    private function generateBasicTemplate()
    {
        $code = '';

        if ($this->includeHeader) {
            $code .= "<!DOCTYPE html>
<html lang=\"ko\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>" . ($this->pageTitle ?: '페이지 제목') . "</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class=\"bg-gray-100\">
";
        }

        $code .= "    <div class=\"min-h-screen\">
        <div class=\"container mx-auto px-4 py-8\">
            <h1 class=\"text-3xl font-bold text-gray-900 mb-6\">" . ($this->pageTitle ?: '페이지 제목') . "</h1>

            <!-- 여기에 콘텐츠를 추가하세요 -->

        </div>
    </div>
";

        if ($this->includeFooter) {
            $code .= "
    <footer class=\"bg-white border-t border-gray-200 mt-12\">
        <div class=\"container mx-auto px-4 py-6\">
            <p class=\"text-center text-gray-600\">© 2024 모든 권리 보유</p>
        </div>
    </footer>
";
        }

        if ($this->includeScripts) {
            $code .= "
    @vite(['resources/js/app.js'])
</body>
</html>";
        } elseif ($this->includeHeader) {
            $code .= "
</body>
</html>";
        }

        return $code;
    }

    private function generateComponentTemplate()
    {
        $code = "@props([])

<div {{ \$attributes->merge(['class' => 'component-class']) }}>
    <!-- 컴포넌트 내용 -->
    <div class=\"p-4 bg-white rounded-lg shadow\">
        <h3 class=\"text-lg font-semibold mb-2\">컴포넌트 제목</h3>
        <p class=\"text-gray-600\">컴포넌트 설명</p>
    </div>
</div>";

        return $code;
    }

    private function generateLayoutTemplate()
    {
        $code = "<!DOCTYPE html>
<html lang=\"ko\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>" . ($this->pageTitle ?: '레이아웃 제목') . "</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class=\"bg-gray-100\">
    <!-- 헤더 -->
    <header class=\"bg-white shadow-sm border-b\">
        <div class=\"container mx-auto px-4 py-4\">
            <h1 class=\"text-2xl font-bold text-gray-900\">" . ($this->pageTitle ?: '사이트 제목') . "</h1>
        </div>
    </header>

    <!-- 메인 콘텐츠 -->
    <main class=\"container mx-auto px-4 py-8\">
        {{ \$slot }}
    </main>

    <!-- 푸터 -->
    <footer class=\"bg-gray-800 text-white mt-12\">
        <div class=\"container mx-auto px-4 py-6\">
            <p class=\"text-center\">© 2024 모든 권리 보유</p>
        </div>
    </footer>

    @vite(['resources/js/app.js'])
</body>
</html>";

        return $code;
    }

    private function generateFormTemplate()
    {
        $code = "<form method=\"POST\" action=\"#\" class=\"space-y-6\">
    @csrf

    <!-- 폼 필드들 -->
    <div>
        <label for=\"name\" class=\"block text-sm font-medium text-gray-700 mb-2\">
            이름
        </label>
        <input type=\"text\"
               id=\"name\"
               name=\"name\"
               value=\"{{ old('name') }}\"
               class=\"w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent\">
        @error('name')
            <p class=\"mt-1 text-sm text-red-600\">{{ \$message }}</p>
        @enderror
    </div>

    <div>
        <label for=\"email\" class=\"block text-sm font-medium text-gray-700 mb-2\">
            이메일
        </label>
        <input type=\"email\"
               id=\"email\"
               name=\"email\"
               value=\"{{ old('email') }}\"
               class=\"w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent\">
        @error('email')
            <p class=\"mt-1 text-sm text-red-600\">{{ \$message }}</p>
        @enderror
    </div>

    <div class=\"flex gap-4\">
        <button type=\"submit\" class=\"bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200\">
            저장
        </button>
        <a href=\"#\" class=\"bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200\">
            취소
        </a>
    </div>
</form>";

        return $code;
    }

    public function copyCode()
    {
        $this->dispatch('code-copied', message: '코드가 클립보드에 복사되었습니다!');
    }

    public function downloadTemplate()
    {
        if (!$this->generatedCode) {
            return;
        }

        $fileName = $this->fileName ?: 'template.blade.php';

        return response()->streamDownload(function () {
            echo $this->generatedCode;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function resetForm()
    {
        $this->templateType = '';
        $this->fileName = '';
        $this->pageTitle = '';
        $this->includeHeader = true;
        $this->includeFooter = true;
        $this->includeScripts = true;
        $this->generatedCode = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.sandbox.blade-creator');
    }
}
