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
        $table->string('file_url'); // Đường dẫn tới file đã nộp
        $table->timestamp('thoi_gian_nop')->useCurrent();
        $table->integer('lan_nop')->default(1);

        // Quan hệ Hợp thành: 1 Bài nộp thuộc về 1 Đồ án
        // 'cascadeOnDelete()' nghĩa là nếu Đồ án bị xóa, Bài nộp này cũng tự động bị xóa.
        $table->foreignId('do_an_id')->constrained('do_an')->cascadeOnDelete();

        $table->timestamps();
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
