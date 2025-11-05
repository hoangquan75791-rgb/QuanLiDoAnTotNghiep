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
    Schema::create('nhan_xet', function (Blueprint $table) {
        $table->id();
        $table->text('noi_dung');

        // Quan hệ: 1 Nhận xét được viết bởi 1 Giảng viên
        $table->foreignId('giang_vien_id')->constrained('users');

        // Quan hệ Hợp thành: 1 Nhận xét thuộc về 1 Đồ án
        $table->foreignId('do_an_id')->constrained('do_an')->cascadeOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_xet');
    }
};
