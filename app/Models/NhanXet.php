<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NhanXet extends Model
{
    use HasFactory;

    /**
     * Tên bảng mà model này quản lý.
     * @var string
     */
    protected $table = 'nhan_xet';

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass-assignable).
     * @var array
     */
    protected $fillable = [
        'noi_dung',
        'bai_nop_id',
        'giang_vien_id',
    ];

    /**
     * Quan hệ: Một nhận xét THUỘC VỀ MỘT bài nộp.
     */
    public function baiNop(): BelongsTo
    {
        return $this->belongsTo(BaiNop::class, 'bai_nop_id');
    }

    /**
     * Quan hệ: Một nhận xét THUỘC VỀ MỘT giảng viên (người viết).
     */
    public function giangVien(): BelongsTo
    {
        // 'giang_vien_id' là khóa ngoại trên bảng 'nhan_xet'
        return $this->belongsTo(User::class, 'giang_vien_id');
    }
}
