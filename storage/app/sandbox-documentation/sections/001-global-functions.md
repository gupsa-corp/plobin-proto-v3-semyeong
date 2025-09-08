# GlobalFunctions 라이브러리 시스템

GlobalFunctions는 프로젝트에서 자주 사용되는 유틸리티 기능들을 모듈화하여 관리하는 시스템입니다.

## 📁 디렉토리 구조

```
app/Http/Sandbox/GlobalFunctions/
├── BaseGlobalFunction.php (추상 베이스 클래스)
├── PHPExcelGenerator.php (Excel 파일 생성)
└── (향후 추가 예정)
    ├── PDFGenerator.php
    ├── EmailSender.php
    ├── FileUploader.php
    └── ...
```

## 🏗️ 아키텍처

### BaseGlobalFunction (추상 클래스)

모든 Global Function의 공통 인터페이스를 제공합니다.

```php
abstract class BaseGlobalFunction
{
    abstract public function getName(): string;
    abstract public function getDescription(): string;
    abstract public function getParameters(): array;
    abstract public function execute(array $params): array;
}
```

**주요 메서드:**
- `validateParams()` - 파라미터 유효성 검증
- `formatResponse()` - 일관된 응답 형식 생성
- `errorResponse()` - 에러 응답 생성
- `successResponse()` - 성공 응답 생성

## 📋 현재 구현된 Functions

### 1. PHPExcelGenerator

Excel 파일을 생성하는 전용 클래스입니다.

**위치:** `app/Http/Sandbox/GlobalFunctions/PHPExcelGenerator.php`

**기능:**
- 2차원 배열 데이터를 Excel로 변환
- 헤더 스타일링 (파란색 배경, 굵은 글꼴)
- 열 너비 자동 조정
- 테두리 적용
- 타임스탬프 기반 파일명 생성

**파라미터:**
```json
{
  "data": [
    ["이름", "나이", "이메일"],
    ["홍길동", 25, "hong@example.com"]
  ],
  "filename": "users.xlsx",
  "sheet_name": "User List",
  "has_headers": true,
  "auto_width": true
}
```

**사용 예시:**
1. Function Browser 접속: `http://localhost:9100/sandbox/function-browser`
2. Global Functions 섹션에서 "PHPExcelGenerator" 선택
3. 파라미터 입력 후 실행
4. 다운로드 링크로 Excel 파일 다운로드

## 🔗 시스템 통합

### Function Browser 통합

`app/Livewire/Sandbox/FunctionBrowser.php`에서 GlobalFunctions를 관리합니다.

**주요 메서드:**
- `loadGlobalFunctions()` - 사용 가능한 함수 목록 로드
- `executeGlobalFunction()` - 함수 실행
- `addGlobalFunctionResult()` - 결과 관리

### 파일 다운로드 시스템

**라우트:** `/sandbox/download/{filename}`
**저장 위치:** `storage/app/sandbox-exports/`
**파일명 규칙:** `{timestamp}_{original_filename}`

**보안 기능:**
- 파일명 정규식 검증
- 허용된 확장자만 다운로드 (xlsx, csv, pdf, txt)
- Path traversal 공격 방지

## 🚀 새로운 Global Function 추가하기

### 1. 클래스 생성

```php
<?php

namespace App\Http\Sandbox\GlobalFunctions;

class YourNewFunction extends BaseGlobalFunction
{
    public function getName(): string
    {
        return 'YourNewFunction';
    }

    public function getDescription(): string
    {
        return '함수 설명';
    }

    public function getParameters(): array
    {
        return [
            'param1' => [
                'required' => true,
                'type' => 'string',
                'description' => '파라미터 설명'
            ]
        ];
    }

    public function execute(array $params): array
    {
        try {
            $this->validateParams($params, ['param1']);
            
            // 실제 로직 구현
            
            return $this->successResponse($result, '성공 메시지');
        } catch (\Exception $e) {
            return $this->errorResponse('에러 메시지: ' . $e->getMessage(), $e);
        }
    }
}
```

### 2. FunctionBrowser에 등록

`app/Livewire/Sandbox/FunctionBrowser.php`의 `loadGlobalFunctions()` 메서드에서:

```php
$globalFunctionClasses = [
    PHPExcelGenerator::class,
    YourNewFunction::class, // 추가
];
```

### 3. Composer 오토로드 업데이트

```bash
composer dump-autoload
```

## 🔧 개발 팁

### 디버깅
- 함수 실행 결과는 Function Browser에서 실시간으로 확인 가능
- 에러 정보는 debug 모드에서 상세히 표시됨
- 파일 생성 실패시 로그 확인

### 성능 최적화
- 대용량 데이터 처리시 메모리 관리 고려
- 파일 생성 후 `disconnectWorksheets()` 호출
- 임시 파일 정리 로직 구현

### 보안 고려사항
- 입력 파라미터 철저한 검증
- 파일 경로 검증
- 민감한 정보 로그 방지

## 📊 확장 계획

### 다음 구현 예정 Functions

1. **PDFGenerator** - PDF 문서 생성
2. **EmailSender** - 이메일 발송
3. **FileUploader** - 파일 업로드 관리
4. **DatabaseConnector** - 다양한 DB 연결
5. **HttpClient** - REST API 호출
6. **JsonParser** - JSON 데이터 처리
7. **DateTimeHelper** - 날짜/시간 유틸리티
8. **StringHelper** - 문자열 처리
9. **ArrayHelper** - 배열 조작

### 향후 개선사항

- 함수 실행 이력 관리
- 배치 실행 기능
- 함수별 권한 설정
- API 엔드포인트 자동 생성
- 함수 성능 모니터링