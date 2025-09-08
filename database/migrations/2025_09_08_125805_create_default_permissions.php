<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 프로젝트 관련 권한들 생성
        $permissions = [
            'project.read' => '프로젝트 읽기',
            'project.write' => '프로젝트 수정',
            'project.deploy' => '배포 관리',
            'project.members' => '멤버 관리',
            'pages.read' => '페이지 조회',
            'pages.write' => '페이지 수정',
            'pages.delete' => '페이지 삭제',
            'project.delete' => '프로젝트 삭제',
            'project.settings' => '프로젝트 설정 관리'
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'project.read',
            'project.write', 
            'project.deploy',
            'project.members',
            'pages.read',
            'pages.write',
            'pages.delete',
            'project.delete',
            'project.settings'
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
};
