<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Đảm bảo đã import các Model liên quan
use App\Models\User;
use App\Models\BaiNop;
use App\Models\NhanXet;
use App\Models\YeuCau; // Model mới

class DoAn extends Model
{
    use HasFactory;

    /**
     * Tên bảng mà model này quản lý.
     * @var string
     */
    protected $table = 'do_an';

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass-assignable).
     */
    protected $fillable = [
        'ten_de_tai',
        'mo_ta',
        'trang_thai',
        'diem_so', // <-- Mới thêm
        'sinh_vien_id',
        'giang_vien_id',
        'giang_vien_phan_bien_id', // <-- Mới thêm
    ];

    /**
     * Lấy sinh viên thực hiện đồ án này (Quan hệ 1-1 ngược)
     */
    public function sinhVien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }

    /**
     * Lấy giảng viên hướng dẫn đồ án này (Quan hệ 1-N ngược)
     */
    public function giangVienHuongDan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'giang_vien_id');
    }

    /**
     * Lấy giảng viên phản biện đồ án này (Quan hệ 1-N ngược) - MỚI
     */
    public function giangVienPhanBien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'giang_vien_phan_bien_id');
    }

    /**
     * Lấy tất cả các bài nộp của đồ án này (Quan hệ Hợp thành 1-N)
     */
    public function baiNops(): HasMany
    {
        return $this->hasMany(BaiNop::class, 'do_an_id');
    }

    /**
     * Lấy tất cả nhận xét của đồ án này (Quan hệ Hợp thành 1-N)
     */
    public function nhanXets(): HasMany
    {
        return $this->hasMany(NhanXet::class, 'do_an_id');
    }
    
    /**
     * Lấy tất cả yêu cầu (gia hạn/phúc khảo) của đồ án này (Quan hệ Hợp thành 1-N) - MỚI
     */
    public function yeuCaus(): HasMany
    {
        return $this->hasMany(YeuCau::class, 'do_an_id');
    }
}
