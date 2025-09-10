# 샌드박스 파일 업로드 시스템

이 시스템은 Laravel 기반의 파일 업로드 및 관리 기능을 제공합니다.

## 📁 디렉토리 구조

```
storage/sandbox/storage-sandbox-template/
├── common.php                         # 공통 설정 및 경로 관리
├── debug-info.php                     # 디버그 정보 확인 페이지
├── frontend/                          # 프론트엔드 템플릿
│   ├── 001-screen-dashboard/              # 대시보드
│   ├── 007-screen-multi-file-upload/     # 다중 파일 업로드 화면
│   ├── 008-screen-uploaded-files-list/   # 업로드 파일 리스트 화면
│   └── [기타 화면들]/                    # 다양한 UI 템플릿
├── backend/                           # 백엔드 로직
│   ├── SandboxHelper.php              # 백엔드 헬퍼 클래스
│   ├── controllers/                   # 컨트롤러
│   ├── services/                      # 서비스 클래스
│   ├── requests/                      # 요청 검증 클래스
│   ├── routes/                        # API 라우트
│   ├── database/                      # 데이터베이스 마이그레이션
│   └── config/                        # 설정 파일
├── uploads/                          # 업로드된 파일 저장 디렉토리 (자동 생성)
├── temp/                             # 임시 파일 디렉토리 (자동 생성)
└── downloads/                        # 다운로드 파일 저장 디렉토리 (자동 생성)
```

## 🔧 경로 관리 시스템

### common.php
모든 프론트엔드 화면에서 현재 위치와 경로 정보를 제공합니다:

```php
<?php 
    require_once __DIR__ . '/../../common.php';
    $screenInfo = getCurrentScreenInfo();
    $uploadPaths = getUploadPaths();
?>
```

### 주요 함수들
- `getCurrentScreenInfo()`: 현재 화면 정보 반환
- `getUploadPaths()`: 업로드 관련 경로 정보 반환  
- `getScreenUrl($type, $name)`: 다른 화면으로의 URL 생성
- `getApiUrl($endpoint)`: API 엔드포인트 URL 생성

### SandboxHelper.php
백엔드에서 샌드박스 경로 정보를 관리합니다:

```php
// 경로 정보
$paths = SandboxHelper::getUploadPaths();

// URL 생성
$screenUrl = SandboxHelper::getScreenUrl('frontend', '007-screen-multi-file-upload');
$apiUrl = SandboxHelper::getApiUrl('file-upload');
```

## 🚀 설치 및 설정

### 1. 데이터베이스 마이그레이션 실행

```bash
php artisan migrate
```

### 2. 스토리지 디렉토리 생성

```bash
mkdir -p storage/sandbox/uploads
mkdir -p storage/sandbox/downloads
chmod -R 755 storage/sandbox/
```

### 3. 샌드박스 화면 접근

샌드박스 시스템의 현재 위치를 자동으로 인식하므로, 다음과 같이 접근 가능합니다:

- 디버그 정보: `/sandbox/storage-sandbox-template/debug-info.php`
- 대시보드: `/sandbox/storage-sandbox-template/frontend/001-screen-dashboard/`
- 다중 파일 업로드: `/sandbox/storage-sandbox-template/frontend/007-screen-multi-file-upload/`
- 업로드 파일 리스트: `/sandbox/storage-sandbox-template/frontend/008-screen-uploaded-files-list/`

> **참고**: 실제 URL은 서버 설정에 따라 다를 수 있습니다. `debug-info.php`에서 정확한 URL을 확인하세요.

### 4. 파일 시스템 동작

시스템은 실제 파일 시스템과 연동되어 동작합니다:

- **업로드**: `frontend/007-screen-multi-file-upload/` → `downloads/` 디렉토리에 저장
- **목록**: `frontend/008-screen-uploaded-files-list/` → `downloads/` 디렉토리의 파일들 표시
- **다운로드**: 직접 파일 시스템 링크를 통한 다운로드

> **동작 방식**: 파일은 `storage/sandbox/storage-sandbox-template/downloads/YYYY/MM/DD/` 구조로 저장됩니다.

## 📋 API 엔드포인트

### 파일 업로드

```http
POST /api/sandbox/file-upload
Content-Type: multipart/form-data

file: [업로드할 파일]
```

**응답:**
```json
{
    "success": true,
    "message": "파일이 성공적으로 업로드되었습니다.",
    "data": {
        "id": 1,
        "original_name": "example.jpg",
        "file_size": 1024000,
        "mime_type": "image/jpeg",
        "uploaded_at": "2025-01-15T10:30:00Z"
    }
}
```

### 파일 목록 조회

```http
GET /api/sandbox/uploaded-files?page=1&per_page=10&search=test&type=image&sort=uploaded_at_desc
```

**파라미터:**
- `page`: 페이지 번호 (기본값: 1)
- `per_page`: 페이지당 항목 수 (기본값: 10, 최대: 100)
- `search`: 검색어 (파일명)
- `type`: 파일 형식 필터 (image, document, video, audio, archive, other)
- `sort`: 정렬 방식 (uploaded_at_desc, uploaded_at_asc, name_asc, name_desc, size_desc, size_asc)

### 파일 다운로드

```http
GET /api/sandbox/uploaded-files/{id}/download
```

### 파일 삭제

```http
DELETE /api/sandbox/uploaded-files/{id}
```

### 파일 통계

```http
GET /api/sandbox/files-stats (지원 예정)
```

## 🎨 프론트엔드 화면

### 다중 파일 업로드 화면 (007-screen-multi-file-upload)

- **헤더**: 아이콘과 설명, 네비게이션 탭
- **통계 카드**: 오늘 업로드 수, 총 파일 수, 사용 용량
- 드래그 앤 드롭 및 파일 선택 지원
- 최대 10MB per file, 총 50MB 제한
- 실시간 업로드 진행률 표시
- 파일 유효성 검사 및 보안 검사
- 반응형 디자인과 그라데이션 배경

### 업로드 파일 리스트 화면 (008-screen-uploaded-files-list)

- **헤더**: 아이콘과 설명, 네비게이션 탭
- **통계 카드**: 총 파일 수, 총 용량, 이미지/문서 파일 수
- 파일 목록 테이블 표시
- 검색, 필터링, 정렬 기능
- 파일 다운로드 및 삭제
- 대량 작업 지원 (선택 다운로드/삭제)
- 페이지네이션과 모달 상세보기

## 🔧 설정 옵션

### 파일 크기 제한

`FileManagerService.php`에서 제한값 수정:

```php
private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB per file
private const MAX_TOTAL_SIZE = 50 * 1024 * 1024; // 50MB total
```

### 지원 파일 형식

`FileUploadRequest.php`에서 지원 형식 추가:

```php
File::types([
    'jpg', 'jpeg', 'png', 'gif', 'webp', // 이미지
    'pdf', 'doc', 'docx', // 문서
    // ... 추가 형식
])
```

## 🛡️ 보안 기능

- 파일 형식 검증 (MIME 타입 + 확장자)
- 파일 크기 제한
- 경로 트래버설 방지
- XSS 공격 방지
- CSRF 토큰 검증
- 파일명 sanitization

## 📊 데이터베이스 스키마

### uploaded_files 테이블

| 필드 | 타입 | 설명 |
|------|------|------|
| id | bigint | Primary Key |
| original_name | varchar(255) | 원본 파일명 |
| stored_name | varchar(255) | 저장된 파일명 |
| file_path | varchar(500) | 저장 경로 |
| file_size | bigint | 파일 크기 (bytes) |
| mime_type | varchar(100) | MIME 타입 |
| uploaded_at | timestamp | 업로드 시간 |
| user_id | bigint | 업로드한 사용자 ID |
| created_at | timestamp | 생성 시간 |
| updated_at | timestamp | 수정 시간 |

## 🔄 파일 저장 구조

```
storage/sandbox/uploads/
├── 2025/
│   ├── 01/
│   │   ├── 15/
│   │   │   ├── 2025-01-15_10-30-00_example.jpg
│   │   │   └── 2025-01-15_10-30-05_document.pdf
│   │   └── 16/
│   │       └── ...
│   └── 02/
│       └── ...
└── ...
```

## 🚀 확장 기능 (선택사항)

### 파일 태그 시스템
- 파일에 태그 추가/제거
- 태그별 파일 필터링

### 파일 공유
- 임시 공유 링크 생성
- 다운로드 제한 및 만료 시간 설정

### 청킹 업로드
- 대용량 파일을 청크로 나누어 업로드
- 업로드 재개 기능

### 이미지 썸네일
- 업로드된 이미지의 썸네일 자동 생성
- 미리보기 기능

## 🐛 문제 해결

### 일반적인 문제

1. **파일 업로드 실패**
   - 스토리지 권한 확인: `chmod -R 755 storage/sandbox/`
   - 디스크 설정 확인: `config/filesystems.php`

2. **파일이 보이지 않음**
   - 데이터베이스 연결 확인
   - 마이그레이션 실행 확인: `php artisan migrate`

3. **다운로드 실패**
   - 파일 존재 확인: `storage/sandbox/uploads/`
   - 파일 권한 확인

### 로그 확인

```bash
# Laravel 로그
tail -f storage/logs/laravel.log

# 파일 업로드 관련 로그
grep "File upload" storage/logs/laravel.log
```

## 📝 개발 노트

- 모든 파일 경로는 보안을 위해 `storage/app/` 외부에 저장
- 데이터베이스 쿼리는 인덱스를 활용하여 최적화
- 파일 삭제 시 물리적 파일과 DB 레코드 모두 삭제
- 정기적인 임시 파일 정리 작업 권장

## 🤝 기여하기

1. 이 저장소를 포크합니다.
2. 기능 브랜치를 생성합니다: `git checkout -b feature/new-feature`
3. 변경사항을 커밋합니다: `git commit -am 'Add new feature'`
4. 브랜치를 푸시합니다: `git push origin feature/new-feature`
5. Pull Request를 생성합니다.
