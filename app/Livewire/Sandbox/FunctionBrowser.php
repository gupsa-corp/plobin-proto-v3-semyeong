<?php

namespace App\Livewire\Sandbox;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class FunctionBrowser extends Component implements HasForms
{
    use InteractsWithForms;

    public string $activeFunction = '';
    public string $activeGroup = '';
    public array $functionContents = [];
    public array $openTabs = [];
    public string $currentStorage = '';
    public array $testResults = [];
    public string $lastTestParams = '{}';
    public array $currentFolderFiles = [];
    public string $selectedFile = '';
    public string $selectedFileContent = '';

    public ?array $data = [];

    public function mount()
    {
        $this->currentStorage = Session::get('sandbox_storage', 'template');
        $this->loadAvailableFunctions();
        
        // 첫 번째 함수를 자동으로 로드
        $functions = $this->getAvailableFunctions();
        if (!empty($functions)) {
            $firstFunction = reset($functions);
            if (!empty($firstFunction['versions'])) {
                $this->loadFunction($firstFunction['name'], 'release');
            }
        }
    }

    /**
     * 사용 가능한 함수 목록 조회
     */
    public function getAvailableFunctions()
    {
        $functionsPath = $this->getFunctionsPath();
        $functions = [];

        if (!File::exists($functionsPath)) {
            return $functions;
        }

        $directories = File::directories($functionsPath);
        
        foreach ($directories as $dir) {
            $functionName = basename($dir);
            $versions = $this->getFunctionVersions($functionName);
            
            if (!empty($versions)) {
                $functions[] = [
                    'name' => $functionName,
                    'versions' => $versions,
                    'path' => $dir,
                    'description' => $this->getFunctionDescription($functionName)
                ];
            }
        }

        return $functions;
    }

    /**
     * 함수 버전 목록 조회
     */
    public function getFunctionVersions($functionName)
    {
        $functionPath = $this->getFunctionsPath() . '/' . $functionName;
        $versions = [];

        if (File::exists($functionPath)) {
            $directories = File::directories($functionPath);
            foreach ($directories as $dir) {
                $versions[] = basename($dir);
            }
            // 최신순 정렬 (release를 맨 위로)
            usort($versions, function($a, $b) {
                if ($a === 'release') return -1;
                if ($b === 'release') return 1;
                return strcmp($b, $a); // 내림차순
            });
        }

        return $versions;
    }

    /**
     * 함수 로드
     */
    public function loadFunction($functionName, $version = 'release')
    {
        $functionPath = $this->getFunctionFilePath($functionName, $version);
        
        if (File::exists($functionPath)) {
            $tabKey = $functionName . ':' . $version;
            
            // 탭에 추가
            if (!in_array($tabKey, $this->openTabs)) {
                $this->openTabs[] = $tabKey;
            }
            
            // 함수 내용 로드
            $this->functionContents[$tabKey] = File::get($functionPath);
            $this->activeFunction = $tabKey;
            $this->activeGroup = $functionName;
            
            // release 폴더인 경우 파일 목록 로드
            if ($version === 'release') {
                $this->loadFolderFiles($functionName, $version);
            } else {
                $this->currentFolderFiles = [];
                $this->selectedFile = '';
                $this->selectedFileContent = '';
            }
            
            $this->dispatch('function-loaded', [
                'function' => $functionName,
                'version' => $version,
                'content' => $this->functionContents[$tabKey]
            ]);
        }
    }

    /**
     * 폴더의 파일 목록 로드
     */
    public function loadFolderFiles($functionName, $version)
    {
        $folderPath = $this->getFunctionDirectoryPath($functionName, $version);
        $this->currentFolderFiles = [];
        
        if (File::exists($folderPath)) {
            $files = File::allFiles($folderPath);
            
            foreach ($files as $file) {
                $relativePath = str_replace($folderPath . '/', '', $file->getPathname());
                $this->currentFolderFiles[] = [
                    'name' => $relativePath,
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime(),
                    'isPhp' => pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php'
                ];
            }
            
            // 파일명으로 정렬
            usort($this->currentFolderFiles, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
        }
    }

    /**
     * 파일 선택
     */
    public function selectFile($fileName)
    {
        $this->selectedFile = $fileName;
        
        // 현재 활성 함수에서 파일 경로 찾기
        if ($this->activeFunction) {
            [$functionName, $version] = explode(':', $this->activeFunction);
            $folderPath = $this->getFunctionDirectoryPath($functionName, $version);
            $filePath = $folderPath . '/' . $fileName;
            
            if (File::exists($filePath)) {
                $this->selectedFileContent = File::get($filePath);
            } else {
                $this->selectedFileContent = '파일을 읽을 수 없습니다.';
            }
        }
    }

    /**
     * 함수 저장 (버전 관리와 함께)
     */
    public function saveFunction($content)
    {
        if (empty($this->activeFunction)) {
            return;
        }

        [$functionName, $currentVersion] = explode(':', $this->activeFunction);
        
        if ($currentVersion === 'release') {
            // 기존 release를 백업 버전으로 이동
            $this->backupCurrentRelease($functionName);
        }
        
        // 새 내용을 release에 저장
        $releasePath = $this->getFunctionFilePath($functionName, 'release');
        File::put($releasePath, $content);
        
        // 메모리의 내용도 업데이트
        $this->functionContents[$this->activeFunction] = $content;
        
        $this->dispatch('function-saved', [
            'function' => $functionName,
            'message' => '함수가 저장되었습니다.'
        ]);
    }

    /**
     * 현재 release 버전을 백업
     */
    private function backupCurrentRelease($functionName)
    {
        $releasePath = $this->getFunctionDirectoryPath($functionName, 'release');
        $backupVersion = 'v' . date('YmdHis');
        $backupPath = $this->getFunctionDirectoryPath($functionName, $backupVersion);
        
        if (File::exists($releasePath)) {
            // 전체 release 디렉토리를 백업 버전으로 복사
            $this->recursiveCopy($releasePath, $backupPath);
        }
    }

    /**
     * 함수 실행
     */
    public function testFunction($params = '{}')
    {
        if (empty($this->activeFunction)) {
            $this->testResults[] = [
                'timestamp' => now()->format('H:i:s'),
                'function' => 'N/A',
                'version' => 'N/A',
                'params' => $params,
                'error' => '함수가 선택되지 않았습니다.',
                'success' => false
            ];
            return;
        }

        [$functionName, $version] = explode(':', $this->activeFunction);
        
        try {
            // JSON 파라미터 검증
            $paramsArray = json_decode($params, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('잘못된 JSON 형식입니다: ' . json_last_error_msg());
            }

            // CommonFunctions 클래스 로드
            $functionsPath = $this->getFunctionsPath();
            $commonFunctionsPath = $functionsPath . '/../Commons/CommonFunctions.php';
            
            if (!file_exists($commonFunctionsPath)) {
                throw new \Exception('CommonFunctions.php 파일을 찾을 수 없습니다.');
            }
            
            require_once $commonFunctionsPath;
            
            if (!class_exists('\App\Commons\CommonFunctions')) {
                throw new \Exception('CommonFunctions 클래스를 로드할 수 없습니다.');
            }
            
            // 함수 실행
            $result = \App\Commons\CommonFunctions::Function($functionName, $version, $paramsArray);
            
            $this->testResults[] = [
                'timestamp' => now()->format('H:i:s'),
                'function' => $functionName,
                'version' => $version,
                'params' => $params,
                'result' => $result,
                'success' => true
            ];
            
        } catch (\Exception $e) {
            $this->testResults[] = [
                'timestamp' => now()->format('H:i:s'),
                'function' => $functionName ?? 'N/A',
                'version' => $version ?? 'N/A',
                'params' => $params,
                'error' => $e->getMessage(),
                'success' => false
            ];
        }

        $this->lastTestParams = $params;
        $this->dispatch('function-tested');
    }

    /**
     * 탭 닫기
     */
    public function closeTab($tabKey)
    {
        $this->openTabs = array_values(array_filter($this->openTabs, fn($tab) => $tab !== $tabKey));
        unset($this->functionContents[$tabKey]);

        if ($this->activeFunction === $tabKey) {
            $this->activeFunction = !empty($this->openTabs) ? $this->openTabs[0] : '';
            if ($this->activeFunction) {
                [$functionName, $version] = explode(':', $this->activeFunction);
                $this->activeGroup = $functionName;
            }
        }
    }

    /**
     * 활성 탭 변경
     */
    public function setActiveTab($tabKey)
    {
        if (in_array($tabKey, $this->openTabs)) {
            $this->activeFunction = $tabKey;
            [$functionName, $version] = explode(':', $tabKey);
            $this->activeGroup = $functionName;
        }
    }

    /**
     * Functions 디렉토리 경로
     */
    private function getFunctionsPath()
    {
        return storage_path('storage-sandbox-' . $this->currentStorage . '/Backend/Functions');
    }

    /**
     * 함수 파일 경로
     */
    private function getFunctionFilePath($functionName, $version)
    {
        return $this->getFunctionsPath() . '/' . $functionName . '/' . $version . '/Function.php';
    }

    /**
     * 함수 디렉토리 경로
     */
    private function getFunctionDirectoryPath($functionName, $version)
    {
        return $this->getFunctionsPath() . '/' . $functionName . '/' . $version;
    }

    /**
     * 함수 설명 조회
     */
    private function getFunctionDescription($functionName)
    {
        $descriptions = [
            'GanttChart' => '간트차트 데이터 관리 및 API 제공',
            'UserAuth' => '사용자 인증 및 권한 관리',
        ];
        
        return $descriptions[$functionName] ?? '함수 설명이 없습니다.';
    }

    /**
     * 디렉토리 재귀 복사
     */
    private function recursiveCopy($source, $destination)
    {
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = File::allFiles($source);
        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $targetPath = $destination . '/' . $relativePath;
            $targetDir = dirname($targetPath);
            
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            File::copy($file->getPathname(), $targetPath);
        }
    }

    /**
     * 사용 가능한 함수 목록을 다시 로드
     */
    private function loadAvailableFunctions()
    {
        // 컴포넌트 초기화 시 필요한 설정
    }

    public function render()
    {
        return view('livewire.sandbox.201-function-browser', [
            'functions' => $this->getAvailableFunctions(),
            'activeContent' => $this->functionContents[$this->activeFunction] ?? '',
            'testResults' => array_reverse($this->testResults),
            'folderFiles' => $this->currentFolderFiles,
            'selectedFileContent' => $this->selectedFileContent,
            'selectedFile' => $this->selectedFile
        ]);
    }
}