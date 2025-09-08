# 샌드박스 도구 모음

개발 효율성을 위한 다양한 유틸리티 도구들입니다.

## 🛠️ 핵심 도구들

### 1. Function Browser
- **경로:** `/sandbox/function-browser`
- **기능:** 마이크로서비스 함수 관리 및 테스트
- **특징:** GlobalFunctions 통합, 실시간 테스트, 파일 다운로드

### 2. SQL 실행기
- **경로:** `/sandbox/sql-executor`
- **기능:** 데이터베이스 쿼리 실행
- **특징:** 실시간 결과 표시, 쿼리 히스토리

### 3. 통합 파일 에디터
- **경로:** `/sandbox/file-editor-integrated`
- **기능:** 파일 매니저 + Monaco 에디터 통합
- **특징:** 드래그 앤 드롭, 실시간 편집

### 4. Form Creator
- **경로:** `/sandbox/form-creator`
- **기능:** 비주얼 폼 빌더
- **특징:** 드래그 앤 드롭으로 폼 생성

### 5. Form Publisher
- **경로:** `/sandbox/form-publisher`
- **기능:** JSON 기반 폼 생성기
- **특징:** 코드로 폼 구조 정의

## 📝 개발 워크플로

### 1. 새 기능 개발 시
1. Function Browser로 함수 프로토타입 생성
2. SQL 실행기로 데이터베이스 스키마 테스트
3. 파일 에디터로 코드 구현
4. Form Creator로 UI 생성

### 2. 디버깅 시
1. SQL 실행기로 데이터 상태 확인
2. Function Browser로 함수 동작 테스트
3. 파일 에디터로 코드 수정

## 🔧 설정 및 사용법

### 환경 설정
```bash
# Composer 의존성 설치
composer install

# 샌드박스 디렉토리 권한 설정
chmod -R 755 storage/app/sandbox-*

# 캐시 초기화
php artisan config:clear
php artisan route:clear
```

### 접근 권한
- 개발 환경에서만 사용 권장
- 프로덕션 환경에서는 접근 제한 필요