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
        Schema::create('project_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->string('status')->default('draft'); // draft, review, published, archived
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('project_pages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->default(0);

            // Access control columns
            $table->string('access_level')->nullable();
            $table->json('allowed_roles')->nullable();

            // Sandbox related columns
            $table->string('sandbox_folder')->nullable();
            $table->string('sandbox_custom_screen_folder')->nullable();

            $table->boolean('custom_screen_enabled')->default(true);
            $table->timestamp('custom_screen_applied_at')->nullable();
            $table->string('template_path')->nullable();

            // Screen configuration columns
            $table->string('screen_title')->nullable();
            $table->text('screen_description')->nullable();
            $table->string('screen_layout')->default('default');
            $table->string('screen_theme')->default('light');
            $table->boolean('screen_fullwidth')->default(false);
            $table->string('screen_sidebar_position')->default('right');
            $table->boolean('screen_header_visible')->default(true);
            $table->boolean('screen_footer_visible')->default(true);
            $table->string('screen_background_color')->nullable();
            $table->string('screen_text_color')->nullable();
            $table->json('screen_custom_css')->nullable();
            $table->json('screen_custom_js')->nullable();
            $table->json('screen_meta_tags')->nullable();
            $table->boolean('screen_responsive')->default(true);
            $table->integer('screen_max_width')->nullable();
            $table->string('screen_font_family')->nullable();
            $table->integer('screen_font_size')->nullable();

            // Soft deletes
            $table->softDeletes();

            $table->timestamps();

            $table->unique(['project_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_pages');
    }
};
