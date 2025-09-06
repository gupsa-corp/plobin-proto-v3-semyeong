# ProjectPageController API 문서

## 개요
프로젝트 대시보드의 탭 페이지 관리 API 컨트롤러

## 주요 기능
- 프로젝트 탭 페이지 목록 조회
- 새 탭 페이지 생성 (링크, 메모, 간트차트 타입)
- 개별 탭 페이지 상세 정보
- 탭 페이지 수정 및 삭제
- 탭 순서 변경

## API 엔드포인트

### 페이지 목록 조회
`GET /api/projects/{project}/tabs`
- 응답: 성공/실패 상태와 페이지 배열
- 탭 전용 페이지만 필터링하여 반환

### 페이지 생성  
`POST /api/projects/{project}/tabs`
- 요청 필드: name(필수), icon, description, config
- 자동 슬러그 생성 및 정렬 순서 설정
- 응답: 생성된 페이지 정보

### 페이지 상세 조회
`GET /api/projects/{project}/tabs/{page}`
- 응답: 개별 페이지 상세 정보

### 페이지 수정
`PUT /api/projects/{project}/tabs/{page}`
- 요청 필드: name(필수), icon, description
- 이름 변경시 슬러그도 자동 업데이트

### 페이지 삭제
`DELETE /api/projects/{project}/tabs/{page}`
- 응답: 삭제 성공/실패 메시지

### 순서 변경
`PUT /api/projects/{project}/tabs/order`
- 요청 필드: pages 배열 (id, sort_order 포함)
- 여러 페이지 순서 일괄 변경

## 데이터 구조

### 페이지 타입별 설정
- iframe: iframeUrl 필드 사용
- text-editor: 기본 텍스트 편집기
- gantt-chart: 간트차트 표시

### JSON 구조
content 필드에 type, description, iframeUrl, icon 정보를 JSON으로 저장

## 오류 처리
모든 메서드에서 try-catch 블록으로 예외 처리
- 422: 유효성 검사 실패  
- 404: 리소스 찾을 수 없음
- 500: 서버 내부 오류

## 주의사항
- 프로젝트 소유권 검증 없이 작동 (개발 환경)
- 인증 미들웨어 제거됨
- 슬러그 중복 방지를 위해 uniqid() 사용