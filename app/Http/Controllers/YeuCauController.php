<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\YeuCau;
use App\Models\DoAn;

class YeuCauController extends Controller
{
    /**
     * Hiển thị trang danh sách yêu cầu và form tạo mới.
     */
    public function index(): View|RedirectResponse
    {
        $sinhVienId = Auth::id();
        
        // 1. Lấy đồ án của sinh viên
        $doAn = DoAn::where('sinh_vien_id', $sinhVienId)->first();

        // Nếu chưa có đồ án, không thể gửi yêu cầu
        if (!$doAn) {
            return redirect()->route('sinhvien.dangky.index')
                             ->with('error', 'Bạn cần có đồ án để gửi yêu cầu.');
        }

        // 2. Lấy lịch sử các yêu cầu đã gửi
        $lichSuYeuCau = YeuCau::where('sinh_vien_id', $sinhVienId)
                              ->where('do_an_id', $doAn->id)
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('sinhvien.yeu-cau.index', [
            'doAn' => $doAn,
            'lichSuYeuCau' => $lichSuYeuCau
        ]);
    }

    /**
     * Xử lý lưu yêu cầu mới.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'loai_yeu_cau' => 'required|in:gia_han,phuc_khao',
            'noi_dung' => 'required|string|min:10',
            // Ngày gia hạn là bắt buộc NẾU loại yêu cầu là 'gia_han'
            'ngay_gia_han' => 'required_if:loai_yeu_cau,gia_han|nullable|date|after:today',
        ]);

        $sinhVienId = Auth::id();
        $doAn = DoAn::where('sinh_vien_id', $sinhVienId)->first();

        if (!$doAn) {
            return back()->with('error', 'Không tìm thấy đồ án.');
        }

        // Tạo yêu cầu mới
        YeuCau::create([
            'sinh_vien_id' => $sinhVienId,
            'do_an_id' => $doAn->id,
            'loai_yeu_cau' => $request->loai_yeu_cau,
            'noi_dung' => $request->noi_dung,
            'ngay_gia_han' => $request->ngay_gia_han,
            'trang_thai' => 'cho_duyet',
        ]);

        return back()->with('success', 'Đã gửi yêu cầu thành công! Vui lòng chờ duyệt.');
    }
}
