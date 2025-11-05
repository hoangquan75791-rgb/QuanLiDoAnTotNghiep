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
    Schema::create('do_an', function (Blueprint $table) {
        $table->id();
        $table->string('ten_de_tai');
        $table->text('mo_ta')->nullable();
        $table->string('trang_thai')->default('chua_dang_ky');
        $table->float('diem_so')->nullable();

        // Quan hệ 1-1: 1 Đồ án thuộc về 1 SinhVien
        // (foreignId 'sinh_vien_id' sẽ liên kết với cột 'id' trên bảng 'users')
        $table->foreignId('sinh_vien_id')->nullable()->constrained('users');

        // Quan hệ 1-N: 1 Đồ án được hướng dẫn bởi 1 GiangVien
        $table->foreignId('giang_vien_id')->nullable()->constrained('users');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_an');
    }
};
