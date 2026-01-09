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
    Schema::table('do_an', function (Blueprint $table) {
        // Thêm cột ID giảng viên phản biện (có thể null nếu chưa phân công)
        $table->unsignedBigInteger('giang_vien_phan_bien_id')->nullable()->after('giang_vien_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('do_an', function (Blueprint $table) {
        $table->dropColumn('giang_vien_phan_bien_id');
    });
}
};
