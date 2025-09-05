{{-- 기본 프로젝트 데이터 함수 --}}
<script>
/**
 * 기본 프로젝트 데이터를 반환합니다
 */
function getDefaultProjects() {
    return [
        {
            name: '웹사이트 리뉴얼',
            status: '진행중',
            members: 4
        },
        {
            name: '모바일 앱 개발',
            status: '진행중',
            members: 3
        },
        {
            name: 'API 서버 구축',
            status: '완료',
            members: 2
        },
        {
            name: 'UI/UX 디자인 시스템',
            status: '일시정지',
            members: 2
        }
    ];
}
</script>