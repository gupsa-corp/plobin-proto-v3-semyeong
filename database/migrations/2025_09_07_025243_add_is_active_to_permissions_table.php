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
        Schema::table('permissions', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('guard_name');
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->unsignedBigInteger('category_id')->nullable()->after('description');
            
            $table->foreign('category_id')->references('id')->on('permission_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['is_active', 'display_name', 'description', 'category_id']);
        });
    }
};
