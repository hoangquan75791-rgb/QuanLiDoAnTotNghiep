<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- THÊM DÒNG NÀY

class BaiNop extends Model
{
    use HasFactory;

    /**
     * Tên bảng mà model này quản lý.
     * @var string
     */
    protected $table = 'bai_nop';

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass-assignable).
     * @var array
     */
    protected $fillable = [
        'tieu_de',
        'file_path', // Đường dẫn tới file đã lưu
        'trang_thai',
        'sinh_vien_id',
        'do_an_id',
    ];

    /**
     * Quan hệ: Một bài nộp THUỘC VỀ MỘT sinh viên.
     */
    public function sinhVien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }

    /**
     * Quan hệ: Một bài nộp THUỘC VỀ MỘT đề tài.
     */
    public function doAn(): BelongsTo
    {
        return $this->belongsTo(DoAn::class, 'do_an_id');
    }

    /**
     * === HÀM MỚI CHO GIAI ĐOẠN 9 ===
     * Quan hệ: Một bài nộp có NHIỀU nhận xét.
     */
    public function nhanXets(): HasMany
    {
        // 'bai_nop_id' là khóa ngoại trên bảng 'nhan_xet'
        return $this->hasMany(NhanXet::class, 'bai_nop_id');
    }
}
