<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; 
use App\Models\DoAn; 
use App\Models\Setting; // Đảm bảo bạn đã import Model Setting

class DeTaiController extends Controller
{
    /**
     * Hiển thị trang quản lý đề tài VÀ danh sách các đề tài đã tạo.
     */
    public function index(): View
    {
        $giangVienId = Auth::id();

        $ds_detai = DoAn::where('giang_vien_id', $giangVienId)
                        ->with('sinhVien') 
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Lấy deadline đăng ký
        $registrationDeadlineSetting = Setting::where('key', 'registration_deadline')->first();
        $registration_deadline = $registrationDeadlineSetting ? $registrationDeadlineSetting->value : null;

        // Lấy deadline nộp bài
        $submissionDeadlineSetting = Setting::where('key', 'submission_deadline')->first();
        $submission_deadline = $submissionDeadlineSetting ? $submissionDeadlineSetting->value : null;

        // Trả về file View, kèm theo biến $ds_detai VÀ CẢ 2 BIẾN DEADLINE
        return view('detai.index', [
            'ds_detai' => $ds_detai,
            'registration_deadline' => $registration_deadline,
            'submission_deadline' => $submission_deadline,
        ]);
    }

    /**
     * Lưu một đề tài mới vào CSDL
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ten_de_tai' => ['required', 'string', 'max:255'],
            'mo_ta' => ['nullable', 'string'],
        ]);

        $giangVienId = Auth::user()->id;

        DoAn::create([
            'ten_de_tai' => $request->ten_de_tai,
            'mo_ta' => $request->mo_ta,
            'giang_vien_id' => $giangVienId,
            'trang_thai' => 'moi_tao',
        ]);

        return redirect()->route('detai.index')->with('success', 'Đã tạo đề tài thành công!');
    }

    /**
     * Hiển thị trang chi tiết cho một đề tài cụ thể.
     */
    public function show(DoAn $doAn): View
    {
        // Tải kèm các thông tin liên quan để hiển thị
        $doAn->load('sinhVien', 'baiNops.nhanXets.giangVien');

        return view('detai.show', [
            'deTai' => $doAn
        ]);
    }

    /**
     * Cập nhật điểm số cho một đồ án.
     */
    public function chamDiem(Request $request, DoAn $doAn): RedirectResponse
    {
        // 1. Kiểm tra bảo mật: Chỉ Giảng viên hướng dẫn mới được chấm điểm
        if (Auth::id() !== $doAn->giang_vien_id) {
            return back()->with('error', 'Bạn không có quyền chấm điểm cho đề tài này.');
        }

        // 2. Xác thực (Validate) điểm số
        $request->validate([
            'diem_so' => 'required|numeric|min:0|max:10',
        ]);

        // 3. Cập nhật điểm và trạng thái
        $doAn->diem_so = $request->diem_so;
        $doAn->trang_thai = 'da_cham_diem'; // Cập nhật trạng thái mới
        $doAn->save();

        // 4. Chuyển hướng lại
        return back()->with('success', 'Đã cập nhật điểm thành công!');
    }
}
