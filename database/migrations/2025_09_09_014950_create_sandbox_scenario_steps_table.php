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
        Schema::create('sandbox_scenario_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_scenario_id')->constrained('sandbox_sub_scenarios')->onDelete('cascade');
            $table->integer('step_number')->unsigned(); // 1-10 사이의 값
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in-progress', 'done', 'blocked'])->default('todo');
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('estimated_hours', 4, 2)->nullable();
            $table->decimal('actual_hours', 4, 2)->nullable();
            $table->json('dependencies')->nullable(); // 선행 단계 ID 배열
            $table->json('attachments')->nullable(); // 첨부파일 정보
            $table->timestamp('completed_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // 유니크 제약조건: 각 서브 시나리오당 단계 번호는 유일해야 함
            $table->unique(['sub_scenario_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandbox_scenario_steps');
    }
};
