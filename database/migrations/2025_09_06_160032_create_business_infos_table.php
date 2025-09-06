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
        Schema::create('business_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('business_name', 100); // 사업체명
            $table->string('business_registration_number', 20)->unique(); // 사업자등록번호
            $table->string('representative_name', 50); // 대표자명
            $table->string('business_type', 50)->nullable(); // 업종
            $table->string('business_item', 100)->nullable(); // 업태
            $table->string('postal_code', 10)->nullable(); // 우편번호
            $table->text('address'); // 주소
            $table->text('detail_address')->nullable(); // 상세주소
            $table->string('phone', 20)->nullable(); // 연락처
            $table->string('fax', 20)->nullable(); // 팩스
            $table->string('email', 100)->nullable(); // 이메일
            $table->timestamps();
            
            $table->index('organization_id');
            $table->index('business_registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_infos');
    }
};
