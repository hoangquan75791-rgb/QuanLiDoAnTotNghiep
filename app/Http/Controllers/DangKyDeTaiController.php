<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse; // Dùng để chuyển hướng
use Illuminate\View\View; // Dùng để trả về view
use Illuminate\Support\Facades\Auth; // Dùng để lấy thông tin user
use App\Models\DoAn; // Import Model DoAn

class DangKyDeTaiController extends Controller
{
    /**
     * Hiển thị trang đăng ký đề tài.
     */
    public function index(): View
    {
        $sinhVienId = Auth::id();
        
        // 1. === CẬP NHẬT TRUY VẤN CHO GIAI ĐOẠN 9 - TASK 2 ===
        // Kiểm tra xem sinh viên này đã đăng ký đề tài nào chưa
        // VÀ tải kèm (with) thông tin giảng viên hướng dẫn
        $deTaiCuaToi = DoAn::where('sinh_vien_id', $sinhVienId)
                           ->with('giangVienHuongDan') // <-- CẬP NHẬT DÒNG NÀY
                           ->first();

        $deTaiSanCo = null;
        if (!$deTaiCuaToi) {
            // 2. Nếu CHƯA đăng ký, lấy danh sách các đề tài CÒN TRỐNG
            $deTaiSanCo = DoAn::where('trang_thai', 'moi_tao')
                              ->whereNull('sinh_vien_id')
                              ->with('giangVienHuongDan') 
                              ->get();
        }

        // 3. Trả về view với dữ liệu tương ứng
        return view('sinhvien.dang-ky', [
            'deTaiCuaToi' => $deTaiCuaToi, // Sẽ là null nếu chưa đăng ký
            'deTaiSanCo' => $deTaiSanCo   // Sẽ là null nếu đã đăng ký
        ]);
    }

    /**
     * Xử lý việc sinh viên đăng ký đề tài.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Xác thực ID đề tài gửi lên là hợp lệ và tồn tại trong bảng 'do_an'
        $request->validate([
            'de_tai_id' => 'required|exists:do_an,id',
        ]);

        $sinhVienId = Auth::id();
        $deTaiId = $request->de_tai_id;

        // 2. Kiểm tra lại lần nữa xem SV này đã đăng ký đề tài nào chưa (để an toàn)
        $daDangKy = DoAn::where('sinh_vien_id', $sinhVienId)->exists();
        if ($daDangKy) {
            return back()->with('error', 'Bạn đã đăng ký một đề tài rồi. Không thể đăng ký thêm.');
        }

        // 3. Tìm đề tài và kiểm tra xem nó có còn trống không (quan trọng!)
        $deTai = DoAn::find($deTaiId);

        if ($deTai->sinh_vien_id || $deTai->trang_thai !== 'moi_tao') {
            return back()->with('error', 'Đề tài này đã có người khác đăng ký hoặc không còn mở.');
        }

        // 4. Cập nhật đề tài: Gán SV và đổi trạng thái
        $deTai->sinh_vien_id = $sinhVienId;
        $deTai->trang_thai = 'da_dang_ky';
        $deTai->save();

        return redirect()->route('sinhvien.dangky.index')->with('success', 'Đăng ký đề tài thành công!');
    }
}
