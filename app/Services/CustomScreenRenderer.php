<?php

namespace App\Services;

class CustomScreenRenderer
{
    public static function render($template, $screenData = [])
    {
        if (empty($template)) {
            return '<div class="bg-red-50 border border-red-200 rounded-lg p-4"><p class="text-red-800">렌더링할 템플릿이 없습니다.</p></div>';
        }

        try {
            // 샘플 데이터 준비
            $sampleData = self::prepareSampleData($screenData);
            
            // 블레이드 템플릿 렌더링 (간단한 시뮬레이션)
            return self::renderBladeTemplate($template, $sampleData);
        } catch (\Exception $e) {
            return '<div class="bg-red-50 border border-red-200 rounded-lg p-4"><p class="text-red-800">렌더링 오류: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    private static function prepareSampleData($screenData)
    {
        // 기본 샘플 데이터
        $sampleData = [
            'title' => $screenData['title'] ?? '제목 없음',
            'description' => $screenData['description'] ?? '',
            'organizations' => [
                [
                    'id' => 1,
                    'name' => '테스트 조직',
                    'description' => '조직 설명',
                    'created_at' => '2025-09-01'
                ],
                [
                    'id' => 2,
                    'name' => '개발팀 조직',
                    'description' => '개발팀 조직 설명',
                    'created_at' => '2025-08-15'
                ],
                [
                    'id' => 3,
                    'name' => '관리자 조직',
                    'description' => '관리자 조직 설명',
                    'created_at' => '2025-07-20'
                ]
            ],
            'projects' => [
                [
                    'id' => 1,
                    'name' => '프로젝트 A',
                    'description' => '프로젝트 A 설명',
                    'created_at' => '2025-09-01'
                ],
                [
                    'id' => 2,
                    'name' => '프로젝트 B',
                    'description' => '프로젝트 B 설명',
                    'created_at' => '2025-08-20'
                ]
            ],
            'users' => [
                [
                    'id' => 1,
                    'name' => '홍길동',
                    'email' => 'hong@example.com',
                    'created_at' => '2025-08-01'
                ],
                [
                    'id' => 2,
                    'name' => '김철수',
                    'email' => 'kim@example.com',
                    'created_at' => '2025-07-15'
                ],
                [
                    'id' => 3,
                    'name' => '이영희',
                    'email' => 'lee@example.com',
                    'created_at' => '2025-07-01'
                ]
            ],
            'activities' => [
                [
                    'id' => 1,
                    'action' => '새로운 프로젝트 "웹 개발 프로젝트"가 생성되었습니다.',
                    'timestamp' => '2025-09-09 14:30:00',
                    'user' => '홍길동'
                ],
                [
                    'id' => 2,
                    'action' => '사용자 "김철수"가 팀에 합류했습니다.',
                    'timestamp' => '2025-09-09 13:15:00',
                    'user' => '관리자'
                ],
                [
                    'id' => 3,
                    'action' => '페이지 "커스텀 화면 설정"이 업데이트되었습니다.',
                    'timestamp' => '2025-09-09 12:45:00',
                    'user' => '이영희'
                ],
                [
                    'id' => 4,
                    'action' => '새로운 커스텀 화면 "활동 로그"가 생성되었습니다.',
                    'timestamp' => '2025-09-09 11:20:00',
                    'user' => '홍길동'
                ],
                [
                    'id' => 5,
                    'action' => '조직 설정이 변경되었습니다.',
                    'timestamp' => '2025-09-09 10:00:00',
                    'user' => '관리자'
                ]
            ]
        ];

        return $sampleData;
    }

    private static function renderBladeTemplate($template, $sampleData)
    {
        $rendered = $template;
        
        // 기본 변수 치환
        foreach ($sampleData as $key => $value) {
            if (is_string($value)) {
                $rendered = str_replace('{{ $' . $key . ' }}', $value, $rendered);
            }
        }

        // @if 처리
        $rendered = self::processIfStatements($rendered, $sampleData);
        
        // @foreach 처리
        $rendered = self::processForeachStatements($rendered, $sampleData);
        
        // @empty 처리
        $rendered = self::processEmptyStatements($rendered, $sampleData);

        return $rendered;
    }

    private static function processIfStatements($rendered, $sampleData)
    {
        // @if($variable) ... @endif 패턴 처리
        $pattern = '/@if\(\$(\w+)\)(.*?)@endif/s';
        $rendered = preg_replace_callback($pattern, function($matches) use ($sampleData) {
            $variable = $matches[1];
            $content = $matches[2];
            
            if (isset($sampleData[$variable]) && !empty($sampleData[$variable])) {
                return $content;
            }
            return '';
        }, $rendered);

        // @if($variable) ... @else ... @endif 패턴 처리
        $pattern = '/@if\(\$(\w+)\)(.*?)@else(.*?)@endif/s';
        $rendered = preg_replace_callback($pattern, function($matches) use ($sampleData) {
            $variable = $matches[1];
            $ifContent = $matches[2];
            $elseContent = $matches[3];
            
            if (isset($sampleData[$variable]) && !empty($sampleData[$variable])) {
                return $ifContent;
            }
            return $elseContent;
        }, $rendered);

        return $rendered;
    }

    private static function processForeachStatements($rendered, $sampleData)
    {
        // @foreach($collection as $item) ... @endforeach 패턴 처리
        $pattern = '/@foreach\(\$(\w+) as \$(\w+)\)(.*?)@endforeach/s';
        
        $rendered = preg_replace_callback($pattern, function($matches) use ($sampleData) {
            $collection = $matches[1];
            $itemName = $matches[2];
            $loopContent = $matches[3];
            
            if (!isset($sampleData[$collection]) || !is_array($sampleData[$collection])) {
                return '';
            }
            
            $result = '';
            foreach ($sampleData[$collection] as $item) {
                $itemContent = $loopContent;
                
                // 배열 항목의 각 속성 치환
                if (is_array($item)) {
                    foreach ($item as $key => $value) {
                        $itemContent = str_replace('{{ $' . $itemName . '[\''. $key .'\'] }}', htmlspecialchars($value), $itemContent);
                        $itemContent = str_replace('{{ $' . $itemName . '[\''.$key.'\'] }}', htmlspecialchars($value), $itemContent);
                    }
                }
                
                $result .= $itemContent;
            }
            
            return $result;
        }, $rendered);

        return $rendered;
    }

    private static function processEmptyStatements($rendered, $sampleData)
    {
        // @empty($variable) ... @endempty 패턴 처리 
        $pattern = '/@empty\(\$(\w+)\)(.*?)@endempty/s';
        $rendered = preg_replace_callback($pattern, function($matches) use ($sampleData) {
            $variable = $matches[1];
            $content = $matches[2];
            
            if (!isset($sampleData[$variable]) || empty($sampleData[$variable])) {
                return $content;
            }
            return '';
        }, $rendered);

        return $rendered;
    }
}