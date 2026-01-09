<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YeuCau extends Model
{
    use HasFactory;

    protected $table = 'yeu_cau';

    protected $fillable = [
        'sinh_vien_id',
        'do_an_id',
        'loai_yeu_cau',
        'ly_do',
        'ngay_gia_han_mong_muon',
        'trang_thai',
        'phan_hoi_nguoi_duyet'
    ];

    public function sinhVien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }

    public function doAn(): BelongsTo
    {
        return $this->belongsTo(DoAn::class, 'do_an_id');
    }
}
