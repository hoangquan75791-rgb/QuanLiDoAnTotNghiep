<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DoAn;
use App\Models\NhanXet;
use App\Models\YeuCau;

class GiangVienController extends Controller
{
    /**
     * Xử lý chấm điểm đồ án
     */
    public function chamDiem(Request $request, $id)
    {
        $request->validate([
            'diem_so' => 'required|numeric|min:0|max:10',
        ]);

        $doAn = DoAn::findOrFail($id);

        // Kiểm tra quyền: Chỉ GV hướng dẫn hoặc GV phản biện mới được chấm
        if (Auth::id() != $doAn->giang_vien_id && Auth::id() != $doAn->giang_vien_phan_bien_id) {
            return back()->with('error', 'Bạn không có quyền chấm điểm đồ án này.');
        }

        $doAn->diem_so = $request->diem_so;
        $doAn->save();

        return back()->with('success', 'Đã cập nhật điểm số thành công!');
    }

    /**
     * Đăng nhận xét cho đồ án
     */
    public function dangNhanXet(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => 'required|string',
        ]);

        $doAn = DoAn::findOrFail($id);

        NhanXet::create([
            'do_an_id' => $doAn->id,
            'giang_vien_id' => Auth::id(),
            'noi_dung' => $request->noi_dung,
        ]);

        return back()->with('success', 'Đã đăng nhận xét thành công!');
    }

    /**
     * Duyệt hoặc Từ chối yêu cầu (Gia hạn/Phúc khảo)
     */
    public function xuLyYeuCau(Request $request, $id)
    {
        $yeuCau = YeuCau::findOrFail($id);

        // Cập nhật trạng thái và phản hồi
        if ($request->has('chap_nhan')) {
            $yeuCau->trang_thai = 'da_duyet';
        } elseif ($request->has('tu_choi')) {
            $yeuCau->trang_thai = 'tu_choi';
        }

        $yeuCau->phan_hoi_nguoi_duyet = $request->phan_hoi;
        $yeuCau->save();

        return back()->with('success', 'Đã xử lý yêu cầu.');
    }
}
