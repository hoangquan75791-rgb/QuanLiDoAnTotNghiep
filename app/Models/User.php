<?php

namespace App\Models;

// Thêm 2 dòng này để import các Model liên quan
use App\Models\DoAn;
use App\Models\NhanXet;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Thêm các cột mới của bạn vào đây để có thể gán hàng loạt
        'vai_tro',
        'ma_so',
        'lop',
        'chuyen_mon',
        'chuc_vu',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Tôi đã sửa lỗi cú pháp 'hy' ở đây
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =================================================================
    // == BẮT ĐẦU CÁC MỐI QUAN HỆ (ĐÃ THÊM TỪ BIỂU ĐỒ LỚP) ==
    // =================================================================

    /**
     * Lấy đồ án mà SinhVien này làm (Quan hệ 1-1)
     */
    public function doAn()
    {
        // 'sinh_vien_id' là khóa ngoại trên bảng 'do_an'
        return $this->hasOne(DoAn::class, 'sinh_vien_id');
    }

    /**
     * Lấy tất cả đồ án mà Giảng viên này hướng dẫn (Quan hệ 1-Nhiều)
     */
    public function doAnHuongDan()
    {
        // 'giang_vien_id' là khóa ngoại trên bảng 'do_an'
        return $this->hasMany(DoAn::class, 'giang_vien_id');
    }

    /**
     * Lấy tất cả nhận xét mà Giảng viên này đã viết (Quan hệ 1-Nhiều)
     */
    public function nhanXetDaViet()
    {
        // 'giang_vien_id' là khóa ngoại trên bảng 'nhan_xet'
        return $this->hasMany(NhanXet::class, 'giang_vien_id');
    }

    // =================================================================
    // == KẾT THÚC CÁC MỐI QUAN HỆ ==
    // =================================================================
}
