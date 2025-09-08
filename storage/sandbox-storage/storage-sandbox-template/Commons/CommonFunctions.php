<?php
/**
 * 마이크로서비스 공통 함수 호출 클래스
 * Function('{함수명}', 'release', [{입력값}]) 패턴으로 모든 함수 호출
 */

namespace App\Commons;

use Exception;

class CommonFunctions
{
    /**
     * @var string 함수들의 기본 경로
     */
    private static $basePath;

    /**
     * @var array 함수 호출 로그
     */
    private static $callLog = [];

    /**
     * 기본 경로 초기화
     */
    private static function initBasePath()
    {
        if (self::$basePath === null) {
            self::$basePath = dirname(__DIR__) . '/Functions';
        }
    }

    /**
     * 마이크로서비스 함수 호출
     * 
     * @param string $functionName 함수명 (예: 'UserAuth')
     * @param string $version 버전 (기본값: 'release')
     * @param array $inputs 입력 데이터 배열
     * @return mixed 함수 실행 결과
     */
    public static function Function($functionName, $version = 'release', $inputs = [])
    {
        self::initBasePath();
        
        try {
            // 호출 로그 기록
            $callId = uniqid();
            self::$callLog[$callId] = [
                'function_name' => $functionName,
                'version' => $version,
                'inputs' => $inputs,
                'timestamp' => time(),
                'start_time' => microtime(true)
            ];

            // 함수 경로 확인
            $functionPath = self::$basePath . '/' . $functionName;
            $versionPath = $functionPath . '/' . $version;

            if (!is_dir($functionPath)) {
                throw new Exception("Function '{$functionName}' does not exist");
            }

            if (!is_dir($versionPath)) {
                throw new Exception("Version '{$version}' not found for function '{$functionName}'");
            }

            // 메인 함수 파일 찾기 (여러 파일명 패턴 지원)
            $possibleFiles = [
                $versionPath . '/' . $functionName . '.php',
                $versionPath . '/Function.php',
                $versionPath . '/index.php'
            ];
            
            $mainFile = null;
            foreach ($possibleFiles as $file) {
                if (file_exists($file)) {
                    $mainFile = $file;
                    break;
                }
            }
            
            if (!$mainFile) {
                throw new Exception("Main function file not found in: " . implode(', ', $possibleFiles));
            }

            // 함수 로드 및 실행
            require_once $mainFile;
            $className = "App\\Functions\\{$functionName}\\{$functionName}";
            
            if (!class_exists($className)) {
                throw new Exception("Function class '{$className}' not found");
            }

            $functionInstance = new $className();
            
            // __invoke 메서드 또는 execute 메서드 사용
            if (method_exists($functionInstance, 'execute')) {
                $result = $functionInstance->execute($inputs);
            } elseif (is_callable($functionInstance)) {
                // __invoke 메서드가 있는 경우
                $action = $inputs['action'] ?? '';
                $data = $inputs['data'] ?? [];
                $result = $functionInstance($action, $data);
            } else {
                throw new Exception("Neither 'execute' method nor '__invoke' method found in class '{$className}'");
            }

            // 함수 실행

            // 완료 로그 기록
            self::$callLog[$callId]['end_time'] = microtime(true);
            self::$callLog[$callId]['execution_time'] = self::$callLog[$callId]['end_time'] - self::$callLog[$callId]['start_time'];
            self::$callLog[$callId]['status'] = 'success';
            self::$callLog[$callId]['result'] = $result;

            return $result;

        } catch (Exception $e) {
            // 에러 로그 기록
            if (isset($callId)) {
                self::$callLog[$callId]['end_time'] = microtime(true);
                self::$callLog[$callId]['execution_time'] = self::$callLog[$callId]['end_time'] - self::$callLog[$callId]['start_time'];
                self::$callLog[$callId]['status'] = 'error';
                self::$callLog[$callId]['error'] = $e->getMessage();
            }

            throw $e;
        }
    }

    /**
     * 사용 가능한 함수 목록 조회
     * 
     * @return array 함수 목록
     */
    public static function getAvailableFunctions()
    {
        self::initBasePath();
        
        $functions = [];
        $basePath = self::$basePath;

        if (is_dir($basePath)) {
            $directories = scandir($basePath);
            foreach ($directories as $dir) {
                if ($dir != '.' && $dir != '..' && is_dir($basePath . '/' . $dir) && $dir != 'CommonFunctions.php') {
                    $functions[] = [
                        'name' => $dir,
                        'versions' => self::getFunctionVersions($dir)
                    ];
                }
            }
        }

        return $functions;
    }

    /**
     * 특정 함수의 버전 목록 조회
     * 
     * @param string $functionName 함수명
     * @return array 버전 목록
     */
    public static function getFunctionVersions($functionName)
    {
        self::initBasePath();
        
        $versions = [];
        $functionPath = self::$basePath . '/' . $functionName;

        if (is_dir($functionPath)) {
            $directories = scandir($functionPath);
            foreach ($directories as $dir) {
                if ($dir != '.' && $dir != '..' && is_dir($functionPath . '/' . $dir)) {
                    $versions[] = $dir;
                }
            }
            // 날짜 기준 정렬 (최신순)
            rsort($versions);
        }

        return $versions;
    }

    /**
     * 새 버전 생성
     * 
     * @param string $functionName 함수명
     * @param string $sourceVersion 복사할 소스 버전 (기본값: 'release')
     * @return string 생성된 버전명 (v{년월일시분초})
     */
    public static function createNewVersion($functionName, $sourceVersion = 'release')
    {
        self::initBasePath();
        
        $functionPath = self::$basePath . '/' . $functionName;
        $sourcePath = $functionPath . '/' . $sourceVersion;

        if (!is_dir($sourcePath)) {
            throw new Exception("Source version '{$sourceVersion}' not found for function '{$functionName}'");
        }

        $newVersion = 'v' . date('YmdHis');
        $newVersionPath = $functionPath . '/' . $newVersion;

        // 디렉토리 복사
        self::recursiveCopy($sourcePath, $newVersionPath);

        return $newVersion;
    }

    /**
     * 함수 호출 로그 조회
     * 
     * @return array 호출 로그
     */
    public static function getCallLog()
    {
        return self::$callLog;
    }

    /**
     * 함수 호출 통계
     * 
     * @return array 통계 정보
     */
    public static function getCallStatistics()
    {
        $stats = [
            'total_calls' => count(self::$callLog),
            'successful_calls' => 0,
            'failed_calls' => 0,
            'functions_used' => [],
            'average_execution_time' => 0,
            'total_execution_time' => 0
        ];

        $totalTime = 0;
        $functionCounts = [];

        foreach (self::$callLog as $log) {
            // 성공/실패 카운트
            if ($log['status'] === 'success') {
                $stats['successful_calls']++;
            } else {
                $stats['failed_calls']++;
            }

            // 함수별 사용 횟수
            $funcName = $log['function_name'];
            if (!isset($functionCounts[$funcName])) {
                $functionCounts[$funcName] = 0;
            }
            $functionCounts[$funcName]++;

            // 실행 시간 누적
            if (isset($log['execution_time'])) {
                $totalTime += $log['execution_time'];
            }
        }

        $stats['functions_used'] = $functionCounts;
        $stats['total_execution_time'] = $totalTime;
        $stats['average_execution_time'] = $stats['total_calls'] > 0 ? $totalTime / $stats['total_calls'] : 0;

        return $stats;
    }

    /**
     * 디렉토리 재귀 복사
     * 
     * @param string $source 소스 디렉토리
     * @param string $destination 대상 디렉토리
     */
    private static function recursiveCopy($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $files = scandir($source);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $sourcePath = $source . '/' . $file;
                $destPath = $destination . '/' . $file;

                if (is_dir($sourcePath)) {
                    self::recursiveCopy($sourcePath, $destPath);
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        }
    }

    /**
     * 함수가 존재하는지 확인
     * 
     * @param string $functionName 함수명
     * @return bool 존재 여부
     */
    public static function functionExists($functionName)
    {
        self::initBasePath();
        
        $functionPath = self::$basePath . '/' . $functionName;
        return is_dir($functionPath);
    }

    /**
     * 함수 정보 조회
     * 
     * @param string $functionName 함수명
     * @param string $version 버전 (기본값: 'release')
     * @return array 함수 정보
     */
    public static function getFunctionInfo($functionName, $version = 'release')
    {
        self::initBasePath();
        
        $functionPath = self::$basePath . '/' . $functionName;
        $versionPath = $functionPath . '/' . $version;
        $infoFile = $versionPath . '/info.json';

        if (!file_exists($infoFile)) {
            return [
                'function_name' => $functionName,
                'version' => $version,
                'status' => 'No info file found',
                'created_at' => filectime($versionPath),
                'modified_at' => filemtime($versionPath)
            ];
        }

        $info = json_decode(file_get_contents($infoFile), true);
        return $info ?: [];
    }
}