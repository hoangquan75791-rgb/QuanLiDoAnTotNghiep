<?php

namespace App\Models;

// Thêm 3 dòng này để import các Model liên quan
use App\Models\User;
use App\Models\BaiNop;
use App\Models\NhanXet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoAn extends Model
{
    use HasFactory;

    /**
     * Tên bảng mà model này quản lý.
     * Laravel thường tự đoán, nhưng khai báo rõ ràng sẽ tốt hơn.
     * @var string
     */
    protected $table = 'do_an';

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass-assignable).
     * @var array
     */
    protected $fillable = [
        'ten_de_tai',
        'mo_ta',
        'trang_thai',
        'diem_so',
        'sinh_vien_id',
        'giang_vien_id',
    ];

    // =================================================================
    // == BẮT ĐẦU CÁC MỐI QUAN HỆ (ĐÃ THÊM TỪ BIỂU ĐỒ LỚP) ==
    // =================================================================

    /**
     * Lấy sinh viên thực hiện đồ án này (Quan hệ 1-1 ngược)
     */
    public function sinhVien()
    {
        // 'sinh_vien_id' là khóa ngoại trên bảng 'do_an'
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }

    /**
     * Lấy giảng viên hướng dẫn đồ án này (Quan hệ 1-N ngược)
     */
    public function giangVienHuongDan()
    {
        // 'giang_vien_id' là khóa ngoại trên bảng 'do_an'
        return $this->belongsTo(User::class, 'giang_vien_id');
    }

    /**
     * Lấy tất cả các bài nộp của đồ án này (Quan hệ Hợp thành 1-N)
     */
    public function baiNops()
    {
        // 'do_an_id' là khóa ngoại trên bảng 'bai_nop'
        return $this->hasMany(BaiNop::class, 'do_an_id');
    }

    /**
     * Lấy tất cả nhận xét của đồ án này (Quan hệ Hợp thành 1-N)
     */
    public function nhanXets()
    {
        // 'do_an_id' là khóa ngoại trên bảng 'nhan_xet'
        return $this->hasMany(NhanXet::class, 'do_an_id');
    }

    // =================================================================
    // == KẾT THÚC CÁC MỐI QUAN HỆ ==
    // =================================================================
}
