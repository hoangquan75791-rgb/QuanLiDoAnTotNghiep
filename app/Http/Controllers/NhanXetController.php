<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\BaiNop;

class NhanXetController extends Controller
{
    /**
     * Lưu một nhận xét mới vào CSDL.
     */
    public function store(Request $request, BaiNop $baiNop): RedirectResponse
    {
        // 1. Xác thực (Validate) dữ liệu đầu vào
        $request->validate([
            'noi_dung' => 'required|string|min:5',
        ]);

        // 2. (Tùy chọn) Kiểm tra bảo mật: 
        // Đảm bảo giảng viên đang đăng nhập là người hướng dẫn của đề tài này
        if (Auth::id() !== $baiNop->doAn->giang_vien_id) {
             return back()->with('error', 'Bạn không có quyền nhận xét bài nộp này.');
        }

        // 3. Tạo nhận xét mới thông qua quan hệ
        $baiNop->nhanXets()->create([
            'noi_dung' => $request->noi_dung,
            'giang_vien_id' => Auth::id(), // Gán ID của giảng viên đang đăng nhập
        ]);

        // 4. Chuyển hướng về trang chi tiết đề tài
        return redirect()->route('detai.show', $baiNop->doAn)
                         ->with('success', 'Đã đăng nhận xét thành công!');
    }
}
