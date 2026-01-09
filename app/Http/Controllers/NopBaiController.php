<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use App\Models\DoAn;
use App\Models\BaiNop;
use App\Models\Setting; // <-- Import Model Setting
use Carbon\Carbon;      // <-- Import thư viện thời gian

class NopBaiController extends Controller
{
    /**
     * Hiển thị trang nộp bài và lịch sử nộp bài.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $sinhVienId = Auth::id();

        // 1. Lấy đề tài mà sinh viên này đang thực hiện
        $deTai = DoAn::where('sinh_vien_id', $sinhVienId)->first();

        // 2. Nếu chưa đăng ký, chuyển hướng về trang đăng ký
        if (!$deTai) {
            return redirect()->route('sinhvien.dangky.index')
                             ->with('error', 'Bạn cần đăng ký đề tài trước khi nộp bài.');
        }

        // 3. Lấy các lần nộp bài trước đó (nếu có)
        // VÀ tải kèm (with) các nhận xét liên quan và tên của giảng viên đã viết nhận xét đó
        $danhSachBaiNop = BaiNop::where('sinh_vien_id', $sinhVienId)
                                ->where('do_an_id', $deTai->id)
                                ->with('nhanXets.giangVien') 
                                ->orderBy('created_at', 'desc')
                                ->get();

        // === CẬP NHẬT GIAI ĐOẠN 11 - TASK 2 ===
        // 4. Kiểm tra Deadline Nộp bài
        $deadlineSetting = Setting::where('key', 'submission_deadline')->first();
        $isSubmissionOpen = true; // Mặc định là mở

        if ($deadlineSetting && $deadlineSetting->value) {
            // Nếu hiện tại > deadline => Đóng
            if (Carbon::now()->gt(Carbon::parse($deadlineSetting->value))) {
                $isSubmissionOpen = false;
            }
        }
        // ======================================

        // 5. Trả về view
        return view('sinhvien.nop-bai', [
            'deTai' => $deTai,
            'danhSachBaiNop' => $danhSachBaiNop,
            'isSubmissionOpen' => $isSubmissionOpen, // Truyền biến kiểm tra sang View
            'deadline' => $deadlineSetting ? $deadlineSetting->value : null
        ]);
    }

    /**
     * Xử lý việc tải file lên.
     */
    public function store(Request $request): RedirectResponse
    {
        $sinhVienId = Auth::id();

        // === CẬP NHẬT GIAI ĐOẠN 11 - TASK 2 (BẢO MẬT) ===
        // 1. Kiểm tra deadline trước khi cho phép upload
        $deadlineSetting = Setting::where('key', 'submission_deadline')->first();
        if ($deadlineSetting && $deadlineSetting->value) {
            if (Carbon::now()->gt(Carbon::parse($deadlineSetting->value))) {
                return back()->with('error', 'Đã hết hạn nộp bài. Bạn không thể tải file lên nữa.');
            }
        }
        // =================================================

        // 2. Xác thực (Validate)
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'file_nop' => 'required|file|mimes:pdf,zip,doc,docx|max:20480', // VD: Tối đa 20MB
        ]);

        // 3. Lấy lại thông tin đề tài
        $deTai = DoAn::where('sinh_vien_id', $sinhVienId)->first();
        if (!$deTai) {
            return back()->with('error', 'Không tìm thấy đề tài của bạn.');
        }

        // 4. Xử lý lưu file
        $path = $request->file('file_nop')->store('public/bai_nop/' . $sinhVienId);

        // 5. Lưu thông tin vào CSDL (bảng bai_nop)
        BaiNop::create([
            'tieu_de' => $request->tieu_de,
            'file_path' => $path, // Lưu đường dẫn
            'trang_thai' => 'da_nop',
            'sinh_vien_id' => $sinhVienId,
            'do_an_id' => $deTai->id,
        ]);

        return back()->with('success', 'Nộp bài thành công!');
    }
}
