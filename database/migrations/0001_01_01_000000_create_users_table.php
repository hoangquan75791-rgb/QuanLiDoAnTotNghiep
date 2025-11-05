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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Cột ID tự tăng (khóa chính)
            $table->string('name'); // Đây là 'hoTen'
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Đây là 'matKhau' (sẽ được mã hóa)

        // --- BẮT ĐẦU CÁC CỘT CHÚNG TA THÊM ---

        // Cột để phân biệt vai trò (từ quan hệ Kế thừa)
            $table->string('vai_tro')->default('sinh_vien'); // Ví dụ: 'sinh_vien', 'giang_vien', 'quan_tri_vien'

        // Cột 'maSo' (chung cho SV và GV)
            $table->string('ma_so')->unique()->nullable(); // 'maSV' hoặc 'maGV', cho phép null

        // Cột riêng cho SinhVien
            $table->string('lop')->nullable(); // Cho phép null vì GV không có

        // Cột riêng cho GiangVien
            $table->string('chuyen_mon')->nullable(); // Cho phép null vì SV không có

        // Cột riêng cho QuanTriVien
            $table->string('chuc_vu')->nullable(); // Cho phép null

        // --- KẾT THÚC CÁC CỘT THÊM ---

            $table->rememberToken();
            $table->timestamps(); // Tự động tạo 2 cột: created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
