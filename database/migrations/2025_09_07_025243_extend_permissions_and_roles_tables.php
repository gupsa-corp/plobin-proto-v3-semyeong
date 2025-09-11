<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extend permissions and roles tables with additional fields
     * - permissions: is_active, display_name, description, category, category_id
     * - roles: description
     */
    public function up(): void
    {
        // Extend permissions table with management fields
        Schema::table('permissions', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('guard_name');
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->string('category')->nullable()->after('description'); // 추가 category 필드
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
            
            $table->foreign('category_id')->references('id')->on('permission_categories')->onDelete('set null');
        });

        // Extend roles table with description field
        Schema::table('roles', function (Blueprint $table) {
            $table->text('description')->nullable()->after('guard_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['is_active', 'display_name', 'description', 'category', 'category_id']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
