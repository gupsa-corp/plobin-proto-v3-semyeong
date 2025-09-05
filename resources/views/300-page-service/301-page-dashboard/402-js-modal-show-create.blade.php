{{-- 조직 생성 모달 보기 함수 --}}
<script>
/**
 * 조직 생성 모달을 보여주는 함수
 */
function showCreateModal() {
    this.isCreateModalOpen = true;
    this.resetForm();
    ModalUtils.showModal('createOrgModal');
    this.$nextTick(() => {
        this.$refs.orgNameInput?.focus();
    });
}
</script>