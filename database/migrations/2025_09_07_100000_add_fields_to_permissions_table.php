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
        // Add category column to permissions if it doesn't exist
        if (!Schema::hasColumn('permissions', 'category')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('category')->nullable()->after('guard_name');
            });
        }

        // Add description to roles table if it doesn't exist
        if (!Schema::hasColumn('roles', 'description')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->text('description')->nullable()->after('guard_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('permissions', 'category')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        if (Schema::hasColumn('roles', 'description')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};