<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User; // Import Model User
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // Cần để kiểm tra ID Admin
use Illuminate\Support\Facades\Redirect; // Cần để chuyển hướng
use Illuminate\Validation\Rule; // Cần cho việc xác thực email
use App\Models\BaiNop; // Cần cho việc xóa user

class AdminController extends Controller
{
    /**
     * Hiển thị trang Quản lý Tài khoản.
     * Đây là trang "dashboard" chính của Admin.
     */
    public function index(): View
    {
        // 1. === DÒNG ĐÃ SỬA LỖI ===
        //    (Đã xóa '->withDefault()')
        $users = User::orderBy('name')->get();

        // 2. Trả về một View
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Hiển thị form để Sửa thông tin một User.
     */
    public function edit(User $user): View
    {
        // $user đã được tự động tìm thấy nhờ Route Model Binding
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Cập nhật thông tin User trong CSDL.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // 1. Xác thực (Validate) dữ liệu
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($user->id) // Email phải là duy nhất, NGOẠI TRỪ user này
            ],
            'ma_so' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique(User::class)->ignore($user->id) // Mã số cũng phải duy nhất
            ],
            'vai_tro' => ['required', 'string', 'in:sinh_vien,giang_vien,quan_tri_vien'],
            'lop' => ['nullable', 'string', 'max:50'],
            'chuyen_mon' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Gán giá trị null nếu không phải vai trò tương ứng
        if ($validated['vai_tro'] === 'sinh_vien') {
            $validated['chuyen_mon'] = null;
        } elseif ($validated['vai_tro'] === 'giang_vien') {
            $validated['lop'] = null;
        }

        // 2. Cập nhật User với dữ liệu đã validate
        $user->update($validated);

        // 3. Chuyển hướng về trang danh sách
        return Redirect::route('admin.dashboard')->with('status', 'user-updated');
    }

    /**
     * Xóa tài khoản User.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // 1. (Bảo mật) Không cho phép Admin tự xóa mình
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của mình.');
        }

        // 2. Xử lý các quan hệ phụ thuộc trước khi xóa
        //    (Nếu không CSDL sẽ báo lỗi khóa ngoại)

        // Nếu User là Giảng viên, gán các đề tài của họ về 'null'
        if ($user->vai_tro === 'giang_vien') {
            // Sử dụng quan hệ doAnHuongDan() trong Model User [cite: User.php]
            $user->doAnHuongDan()->update(['giang_vien_id' => null]);
            // (Bạn cũng có thể xóa luôn các nhận xét của GV này nếu muốn)
            // $user->nhanXetDaViet()->delete();
        }
        
        // Nếu User là Sinh viên, gán đề tài của họ về 'null' và reset trạng thái
        if ($user->vai_tro === 'sinh_vien' && $user->doAn) {
            // Sử dụng quan hệ doAn() trong Model User [cite: User.php]
            $user->doAn->update(['sinh_vien_id' => null, 'trang_thai' => 'moi_tao']);
            // Xóa các bài nộp của sinh viên này
            BaiNop::where('sinh_vien_id', $user->id)->delete();
        }
        
        // 3. Xóa User
        $user->delete();

        // 4. Chuyển hướng về trang danh sách
        return back()->with('status', 'user-deleted');
    }
}
