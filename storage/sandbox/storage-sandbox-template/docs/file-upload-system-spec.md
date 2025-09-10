# 파일 업로드 시스템 스펙

## 개요
스토리지 샌드박스 템플릿에 파일 업로드 및 관리 시스템을 구축합니다. 사용자가 여러 파일을 동시에 업로드하고, 업로드된 파일 목록을 확인할 수 있는 기능을 제공합니다.

## 요구사항

### 1. 여러 파일 업로드 기능
- **화면 ID**: 007-screen-multi-file-upload
- **위치**: `storage/sandbox/storage-sandbox-template/frontend/007-screen-multi-file-upload/`
- **기능**:
  - 드래그 앤 드롭으로 파일 선택 가능
  - 파일 선택 버튼으로 다중 파일 선택
  - 파일 크기 제한: 10MB per file, 총 50MB
  - 지원 파일 형식: 모든 파일 형식 허용
  - 업로드 진행률 표시
  - 파일 유효성 검사 (이름 중복, 크기, 형식)
  - 업로드 성공/실패 알림

### 2. 업로드 파일 리스트 기능
- **화면 ID**: 008-screen-uploaded-files-list
- **위치**: `storage/sandbox/storage-sandbox-template/frontend/008-screen-uploaded-files-list/`
- **기능**:
  - 업로드된 파일 목록 표시
  - 파일 정보: 이름, 크기, 업로드 날짜, 형식
  - 파일 다운로드 링크
  - 파일 삭제 기능
  - 파일 검색 및 필터링
  - 정렬 기능 (이름, 날짜, 크기)
  - 페이지네이션

### 3. 파일 저장 구조
- **업로드 경로**: `storage/sandbox/storage-sandbox-template/uploads/`
- **다운로드 경로**: `storage/sandbox/storage-sandbox-template/downloads/`
- **파일명 규칙**: `{timestamp}_{original_filename}`
- **폴더 구조**:
  ```
  uploads/
    YYYY/
      MM/
        DD/
          files...
  ```

### 4. 백엔드 로직
- **위치**: `storage/sandbox/storage-sandbox-template/backend/`
- **필요한 파일들**:
  - `FileUploadController.php` - 파일 업로드 처리
  - `FileManagerService.php` - 파일 관리 서비스
  - `FileUploadRequest.php` - 유효성 검사
  - `routes.php` - API 라우트
- **기능**:
  - 파일 업로드 처리
  - 파일 메타데이터 저장
  - 파일 다운로드 처리
  - 파일 삭제 처리
  - 파일 검색 및 필터링

### 5. 데이터베이스 구조
- **테이블**: uploaded_files
- **필드**:
  - id (primary key)
  - original_name (varchar)
  - stored_name (varchar)
  - file_path (varchar)
  - file_size (bigint)
  - mime_type (varchar)
  - uploaded_at (timestamp)
  - user_id (int, nullable)

## 기술 스택
- 프론트엔드: HTML, CSS, JavaScript (Vanilla or jQuery)
- 백엔드: PHP (Laravel 프레임워크 기반)
- 데이터베이스: SQLite (샌드박스 환경)
- 파일 저장: 로컬 파일시스템

## UI/UX 요구사항
- 반응형 디자인
- 직관적인 사용자 인터페이스
- 진행 상태 표시
- 오류 처리 및 사용자 피드백
- 접근성 고려

## 보안 고려사항
- 파일 형식 검증
- 파일 크기 제한
- 경로 트래버설 방지
- XSS 공격 방지
- CSRF 토큰 사용

## 테스트 시나리오
1. 단일 파일 업로드
2. 다중 파일 업로드
3. 대용량 파일 업로드
4. 허용되지 않은 파일 형식 업로드 시도
5. 파일 다운로드
6. 파일 삭제
7. 파일 검색 및 필터링
