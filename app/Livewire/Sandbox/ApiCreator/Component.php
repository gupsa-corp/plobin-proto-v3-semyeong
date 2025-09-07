<?php

namespace App\Livewire\Sandbox\ApiCreator;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\File;

class Component extends LivewireComponent
{
    public $apiCode = '';
    public $apiName = '';
    public $apiDescription = '';

    public function render()
    {
        return view('700-page-sandbox.703-livewire-api-creator');
    }

    public function saveApi()
    {
        $this->validate([
            'apiName' => 'required|min:3',
            'apiCode' => 'required|min:10',
            'apiDescription' => 'required|min:5',
        ], [
            'apiName.required' => 'API 이름을 입력하세요.',
            'apiName.min' => 'API 이름은 최소 3자 이상이어야 합니다.',
            'apiCode.required' => 'API 코드를 입력하세요.',
            'apiCode.min' => 'API 코드는 최소 10자 이상이어야 합니다.',
            'apiDescription.required' => 'API 설명을 입력하세요.',
            'apiDescription.min' => 'API 설명은 최소 5자 이상이어야 합니다.',
        ]);

        try {
            $fileName = $this->sanitizeFileName($this->apiName);
            $filePath = storage_path('sandbox/api/' . $fileName . '.php');
            
            // 디렉토리 생성
            if (!File::exists(dirname($filePath))) {
                File::makeDirectory(dirname($filePath), 0755, true);
            }

            // 메타데이터와 함께 파일 저장
            $fileContent = "<?php\n\n";
            $fileContent .= "/**\n";
            $fileContent .= " * API Name: {$this->apiName}\n";
            $fileContent .= " * Description: {$this->apiDescription}\n";
            $fileContent .= " * Created: " . now()->format('Y-m-d H:i:s') . "\n";
            $fileContent .= " */\n\n";
            $fileContent .= $this->apiCode;

            File::put($filePath, $fileContent);

            session()->flash('message', "API '{$this->apiName}'가 성공적으로 저장되었습니다. 경로: {$filePath}");
            
        } catch (\Exception $e) {
            session()->flash('error', 'API 저장 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function testApi()
    {
        $this->validate([
            'apiCode' => 'required|min:10',
        ], [
            'apiCode.required' => 'API 코드를 입력하세요.',
            'apiCode.min' => 'API 코드는 최소 10자 이상이어야 합니다.',
        ]);

        try {
            // 기본적인 PHP 문법 검사
            if (!$this->validatePhpSyntax($this->apiCode)) {
                session()->flash('error', 'PHP 문법 오류가 있습니다. 코드를 확인하세요.');
                return;
            }

            session()->flash('message', 'API 코드 문법 검사가 완료되었습니다. 문법적 오류가 없습니다.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'API 테스트 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    private function sanitizeFileName($filename)
    {
        // 파일명에서 특수문자 제거하고 안전한 이름으로 변환
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        return trim($filename, '_');
    }

    private function validatePhpSyntax($code)
    {
        // PHP 문법 검사를 위한 임시 파일 생성
        $tempFile = tempnam(sys_get_temp_dir(), 'php_syntax_check');
        file_put_contents($tempFile, $code);
        
        $output = [];
        $return_var = 0;
        exec("php -l {$tempFile} 2>&1", $output, $return_var);
        
        unlink($tempFile);
        
        return $return_var === 0;
    }
}