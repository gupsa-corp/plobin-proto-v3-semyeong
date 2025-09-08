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
        Schema::create('sandbox_scenario_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sandbox_scenario_id')->constrained('sandbox_scenarios')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('sandbox_scenario_requirements')->onDelete('cascade');
            $table->text('content');
            $table->boolean('completed')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['sandbox_scenario_id', 'parent_id']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandbox_scenario_requirements');
    }
};
