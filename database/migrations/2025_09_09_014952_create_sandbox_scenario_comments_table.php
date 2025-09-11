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
        Schema::create('sandbox_scenario_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scenario_id')->nullable()->constrained('sandbox_scenarios')->onDelete('cascade');
            $table->foreignId('sub_scenario_id')->nullable()->constrained('sandbox_sub_scenarios')->onDelete('cascade');
            $table->foreignId('step_id')->nullable()->constrained('sandbox_scenario_steps')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->timestamps();

            // 한 개의 댓글은 시나리오, 서브 시나리오, 단계 중 하나에만 연결될 수 있음
            // (모델 레벨에서 검증)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandbox_scenario_comments');
    }
};
