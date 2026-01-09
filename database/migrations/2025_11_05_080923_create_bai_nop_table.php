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
    Schema::create('bai_nop', function (Blueprint $table) {
        $table->id();
        $table->string('tieu_de');
        $table->string('file_path');
        $table->string('trang_thai')->default('da_nop');
        
        // === HAI DÒNG QUAN TRỌNG NÀY ĐANG BỊ THIẾU HOẶC SAI ===
        $table->unsignedBigInteger('sinh_vien_id');
        $table->unsignedBigInteger('do_an_id');
        // ======================================================

        $table->timestamps();

        // (Tùy chọn) Tạo khóa ngoại để bảo vệ dữ liệu
        // $table->foreign('sinh_vien_id')->references('id')->on('users');
        // $table->foreign('do_an_id')->references('id')->on('do_an');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_nop');
    }
};
