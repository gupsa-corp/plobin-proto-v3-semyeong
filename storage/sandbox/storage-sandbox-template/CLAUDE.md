# 샌드박스 커스텀 화면 시스템 가이드

## 개요

이 샌드박스는 파일 기반 커스텀 화면 시스템을 사용합니다. 화면은 실제 `.blade.php` 파일로 저장되며, 데이터베이스에는 메타데이터만 저장됩니다.

## 파일 구조

### 기본 디렉토리 구조
```
storage/sandbox/storage-sandbox-{type}/
├── custom-screens/
│   ├── 000-screen-{화면명}/
│   │   └── 000-content.blade.php
│   ├── 001-screen-{화면명}/
│   │   └── 000-content.blade.php
│   └── ...
├── database/
│   └── sqlite.db
└── CLAUDE.md (이 파일)
```

### 파일명 규칙
- **폴더**: `000-screen-{화면명}/`, `001-screen-{화면명}/` 등
- **파일**: 폴더 안에 `000-content.blade.php` 고정
- 예시: `000-screen-dashboard/000-content.blade.php`

## 데이터베이스 스키마

### sandbox_custom_screens 테이블
메인 Laravel 데이터베이스에 저장됩니다 (샌드박스 SQLite가 아님):

```sql
CREATE TABLE sandbox_custom_screens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,            -- 화면 제목
    description TEXT NULL,                  -- 화면 설명  
    type VARCHAR(255) DEFAULT 'dashboard',  -- 화면 타입 (dashboard, list, form, detail, report)
    folder_name VARCHAR(255) NOT NULL UNIQUE, -- 폴더명 (예: 000-screen-dashboard)
    file_path VARCHAR(255) NOT NULL,       -- 파일 경로 (예: custom-screens/000-screen-dashboard/000-content.blade.php)
    sandbox_type VARCHAR(255) DEFAULT 'template', -- 샌드박스 타입 (template, custom 등)
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Blade 템플릿 개발 가이드

### 사용 가능한 기술 스택
- **Blade**: Laravel 블레이드 문법 전체 사용 가능
- **Livewire**: 컴포넌트 및 상호작용 처리
- **TailwindCSS**: 스타일링 (모든 클래스 사용 가능)
- **Alpine.js**: 기본적인 JavaScript 상호작용

### 샘플 데이터
템플릿에서 사용할 수 있는 기본 변수들:

```php
$title           // 화면 제목
$description     // 화면 설명
$organizations   // 조직 목록 배열
$projects        // 프로젝트 목록 배열  
$users          // 사용자 목록 배열
$activities     // 활동 로그 배열
```

### 예시 템플릿 구조

#### 대시보드 예시
```blade
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $title }}</h2>
    <p class="text-gray-600 mb-6">{{ $description }}</p>
    
    <!-- 통계 카드 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">전체 조직</h3>
            <p class="text-3xl font-bold text-blue-600">{{ count($organizations) }}개</p>
        </div>
        <!-- 더 많은 카드... -->
    </div>
    
    <!-- 데이터 테이블/목록 -->
    <div class="space-y-4">
        @foreach($activities as $activity)
            <div class="p-3 bg-gray-50 rounded border">
                <p class="font-medium">{{ $activity['action'] }}</p>
                <div class="flex justify-between text-sm text-gray-500 mt-1">
                    <span>{{ $activity['user'] }}</span>
                    <span>{{ $activity['timestamp'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
```

#### Livewire 사용 예시
```blade
<div class="mt-8">
    <!-- 정적 콘텐츠 -->
    <h3 class="text-lg font-semibold mb-4">실시간 데이터</h3>
    
    <!-- Livewire 컴포넌트 영역 -->
    <div class="p-4 bg-blue-50 rounded-lg">
        <p class="text-blue-600 mb-4">여기서 Livewire 컴포넌트를 사용할 수 있습니다.</p>
        
        <!-- Alpine.js 상호작용 -->
        <div x-data="{ open: false }">
            <button @click="open = !open" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                토글 버튼
            </button>
            
            <div x-show="open" x-transition class="mt-4 p-3 bg-white rounded border">
                <p>토글된 콘텐츠입니다!</p>
            </div>
        </div>
    </div>
</div>
```

## 화면 개발 워크플로우

### 1. 새 화면 생성
1. 폴더 생성: `custom-screens/001-screen-{화면명}/`
2. 파일 생성: `000-content.blade.php`
3. DB 메타데이터 추가 (Laravel Eloquent 사용):
```php
use App\Models\SandboxCustomScreen;

SandboxCustomScreen::create([
    'title' => '화면제목',
    'description' => '화면설명', 
    'type' => 'dashboard',
    'folder_name' => '001-screen-화면명',
    'file_path' => 'custom-screens/001-screen-화면명/000-content.blade.php',
    'sandbox_type' => 'template' // 또는 'custom'
]);
```

### 2. 화면 수정
1. `000-content.blade.php` 파일 직접 편집
2. 브라우저에서 실시간 확인
3. 필요시 DB의 title, description, type 업데이트

### 3. 화면 적용
1. 샌드박스 화면 브라우저(`/sandbox/custom-screens`)에서 확인
2. 프로젝트 페이지 설정에서 커스텀 화면 선택
3. 프로젝트 페이지에서 실제 렌더링 확인

## 주의사항

### 보안 규칙
- 파일은 반드시 `storage/sandbox/` 디렉토리 내에 위치
- 위험한 PHP 함수 사용 금지: `exec`, `shell_exec`, `system`, `file_get_contents` 등
- 파일명은 `.blade.php`로 끝나야 함

### 성능 최적화
- 복잡한 로직은 Livewire 컴포넌트로 분리
- 대용량 데이터는 페이지네이션 사용
- 이미지/에셋은 public 경로 사용

### 호환성
- 기존 템플릿 문자열 기반 시스템과 호환
- Laravel Blade 문법 완전 지원
- TailwindCSS 클래스 모두 사용 가능

## 샘플 화면 목록

현재 샌드박스에 포함된 샘플 화면들:

1. **샘플 대시보드** (`000-screen-dashboard`)
   - 유형: dashboard
   - 설명: 기본적인 대시보드 레이아웃과 통계 카드

2. **프로젝트 목록** (`001-screen-project-list`)  
   - 유형: list
   - 설명: 프로젝트 관리를 위한 테이블 형식 목록

이 샘플들을 참고하여 새로운 커스텀 화면을 개발할 수 있습니다.

## 트러블슈팅

### 화면이 렌더링되지 않는 경우
1. 파일 경로 확인: `file_path`가 정확한지 확인
2. 파일 권한 확인: 읽기 권한이 있는지 확인  
3. 문법 오류 확인: Blade 문법이 올바른지 확인
4. 로그 확인: Laravel 로그에서 오류 메시지 확인

### 데이터가 표시되지 않는 경우
1. 변수명 확인: `$organizations`, `$projects` 등 정확한 변수명 사용
2. 배열 구조 확인: `count()` 함수 사용 시 배열인지 확인
3. 조건문 확인: `@if`, `@foreach` 문법이 올바른지 확인

이 가이드를 참고하여 효과적인 커스텀 화면을 개발하세요!