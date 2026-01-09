<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// Bỏ "Rules" vì chúng ta sẽ dùng 'min:8' đơn giản
// use Illuminate\Validation\Rules; 
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // === BƯỚC 1: SỬA LOGIC VALIDATION ===
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            
            // YÊU CẦU MẬT KHẨU MỚI: Chỉ cần yêu cầu (required), 
            // được xác nhận (confirmed) và tối thiểu 8 ký tự (min:8)
            'password' => ['required', 'confirmed', 'min:8'], 

            'ma_so' => ['required', 'string', 'max:50', 'unique:'.User::class],
            'vai_tro' => ['required', 'string', 'in:sinh_vien,giang_vien'], 
            'lop' => ['required_if:vai_tro,sinh_vien', 'nullable', 'string', 'max:50'], 
            'chuyen_mon' => ['required_if:vai_tro,giang_vien', 'nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect('register')
                        ->withErrors($validator)
                        ->withInput();
        }

        // === BƯỚC 2: DỌN DẸP DỮ LIỆU TRƯỚC KHI LƯU ===
        // Lấy tất cả dữ liệu đã được xác thực
        $data = $validator->validated();

        // Nếu là Sinh viên, ép Chuyên môn = null
        if ($data['vai_tro'] === 'sinh_vien') {
            $data['chuyen_mon'] = null;
        } 
        // Nếu là Giảng viên, ép Lớp = null
        elseif ($data['vai_tro'] === 'giang_vien') {
            $data['lop'] = null;
        }

        // === BƯỚC 3: TẠO USER TỪ DỮ LIỆU ĐÃ LÀM SẠCH ===
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'ma_so' => $data['ma_so'],
            'vai_tro' => $data['vai_tro'],
            'lop' => $data['lop'],
            'chuyen_mon' => $data['chuyen_mon'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
