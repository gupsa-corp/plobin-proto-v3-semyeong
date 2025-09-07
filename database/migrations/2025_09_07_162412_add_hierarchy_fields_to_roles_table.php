<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // 이미 존재하지 않는 컬럼만 추가
            
            // 활성화 상태 (아직 없는 것 같음)
            if (!Schema::hasColumn('roles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('created_by');
            }
            
            // 인덱스 추가 (존재하지 않는 경우에만)
            $existingIndexes = Schema::getIndexes('roles');
            $existingIndexNames = array_column($existingIndexes, 'name');
            
            if (!in_array('roles_scope_org_index', $existingIndexNames)) {
                $table->index(['scope_level', 'organization_id'], 'roles_scope_org_index');
            }
            if (!in_array('roles_scope_project_index', $existingIndexNames)) {
                $table->index(['scope_level', 'project_id'], 'roles_scope_project_index');
            }
            if (!in_array('roles_scope_page_index', $existingIndexNames)) {
                $table->index(['scope_level', 'page_id'], 'roles_scope_page_index');
            }
            if (!in_array('roles_parent_index', $existingIndexNames)) {
                $table->index('parent_role_id', 'roles_parent_index');
            }
            if (!in_array('roles_creator_index', $existingIndexNames)) {
                $table->index('created_by', 'roles_creator_index');
            }
            
            // 외래 키 제약 조건 추가 (존재하지 않는 경우에만)
            $existingForeignKeys = Schema::getForeignKeys('roles');
            $existingForeignKeyColumns = array_column($existingForeignKeys, 'columns');
            
            if (!in_array(['organization_id'], $existingForeignKeyColumns)) {
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            }
            if (!in_array(['created_by'], $existingForeignKeyColumns)) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!in_array(['parent_role_id'], $existingForeignKeyColumns)) {
                $table->foreign('parent_role_id')->references('id')->on('roles')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // 이 마이그레이션에서 추가한 것들만 삭제
            
            // 외래 키 제약 조건 삭제 (존재하는 경우에만)
            $existingForeignKeys = Schema::getForeignKeys('roles');
            $existingForeignKeyColumns = array_column($existingForeignKeys, 'columns');
            
            if (in_array(['organization_id'], $existingForeignKeyColumns)) {
                $table->dropForeign(['organization_id']);
            }
            if (in_array(['created_by'], $existingForeignKeyColumns)) {
                $table->dropForeign(['created_by']);
            }
            if (in_array(['parent_role_id'], $existingForeignKeyColumns)) {
                $table->dropForeign(['parent_role_id']);
            }
            
            // 인덱스 삭제 (존재하는 경우에만)
            $existingIndexes = Schema::getIndexes('roles');
            $existingIndexNames = array_column($existingIndexes, 'name');
            
            if (in_array('roles_scope_org_index', $existingIndexNames)) {
                $table->dropIndex('roles_scope_org_index');
            }
            if (in_array('roles_scope_project_index', $existingIndexNames)) {
                $table->dropIndex('roles_scope_project_index');
            }
            if (in_array('roles_scope_page_index', $existingIndexNames)) {
                $table->dropIndex('roles_scope_page_index');
            }
            if (in_array('roles_parent_index', $existingIndexNames)) {
                $table->dropIndex('roles_parent_index');
            }
            if (in_array('roles_creator_index', $existingIndexNames)) {
                $table->dropIndex('roles_creator_index');
            }
            
            // 이 마이그레이션에서 추가한 컬럼만 삭제
            if (Schema::hasColumn('roles', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
