<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_cau', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sinh_vien_id');
            $table->unsignedBigInteger('do_an_id');
            
            // Loại yêu cầu: 'gia_han' hoặc 'phuc_khao'
            $table->enum('loai_yeu_cau', ['gia_han', 'phuc_khao']);
            
            $table->text('ly_do'); // Lý do của sinh viên
            
            // Chỉ dùng cho yêu cầu gia hạn
            $table->date('thoi_gian_gia_han_mong_muon')->nullable(); 
            
            // Trạng thái xử lý
            $table->enum('trang_thai', ['cho_duyet', 'chap_nhan', 'tu_choi'])->default('cho_duyet');
            
            // Phản hồi của giảng viên/admin
            $table->text('phan_hoi_cua_gv')->nullable(); 
            
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('sinh_vien_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('do_an_id')->references('id')->on('do_an')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_cau');
    }
};
