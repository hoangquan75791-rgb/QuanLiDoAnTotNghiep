<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect; // <-- ĐẢM BẢO CÓ DÒNG NÀY
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DeTaiController;
use App\Http\Controllers\DangKyDeTaiController;
use App\Http\Controllers\NopBaiController;
use App\Http\Controllers\NhanXetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\GiangVienController; // <-- THÊM DÒNG NÀY

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === SỬA LỖI 404 ===
// Tự động chuyển hướng trang chủ (/) sang trang đăng nhập (/login)
Route::get('/', function () {
    return Redirect::route('login');
});

// Route Dashboard mặc định của Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes Profile mặc định của Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === ROUTES CHO SINH VIÊN VÀ GIẢNG VIÊN (SPRINT 3 & 4) ===
Route::middleware(['auth', 'verified'])->group(function () {

    // Task 1: Quản lý đề tài (Giảng viên)
    Route::get('/quan-ly-de-tai', [DeTaiController::class, 'index'])
         ->name('detai.index');
    Route::post('/quan-ly-de-tai', [DeTaiController::class, 'store'])
         ->name('detai.store');
    // ...

    // Task: Sinh viên gửi yêu cầu
    Route::get('/sinh-vien/yeu-cau', [YeuCauController::class, 'index'])->name('sinhvien.yeucau.index');
    Route::post('/sinh-vien/yeu-cau', [YeuCauController::class, 'store'])->name('sinhvien.yeucau.store');
    // Task 2: Đăng ký đề tài (Sinh viên)
    Route::get('/dang-ky-de-tai', [DangKyDeTaiController::class, 'index'])
         ->name('sinhvien.dangky.index');
    Route::post('/dang-ky-de-tai', [DangKyDeTaiController::class, 'store'])
         ->name('sinhvien.dangky.store');

    // Task 3: Nộp bài (Sinh viên)
    Route::get('/nop-bai', [NopBaiController::class, 'index'])
         ->name('sinhvien.nopbai.index');
    Route::post('/nop-bai', [NopBaiController::class, 'store'])
         ->name('sinhvien.nopbai.store');

    // Task 1 (Sprint 4): Hiển thị chi tiết đề tài (Giảng viên)
    Route::get('/de-tai/{doAn}', [DeTaiController::class, 'show'])
         ->name('detai.show');
         
    // Task 2 (Sprint 4): Giảng viên đăng nhận xét (GV)
    Route::post('/bai-nop/{baiNop}/nhan-xet', [NhanXetController::class, 'store'])
         ->name('nhanxet.store');

    // Task 3 (Sprint 4): Giảng viên chấm điểm (GV)
    Route::post('/de-tai/{doAn}/cham-diem', [DeTaiController::class, 'chamDiem'])
         ->name('detai.chamdiem');

    // === ROUTE MỚI CHO GIẢNG VIÊN (Giai đoạn 12) ===

    // Chấm điểm (route mới)
    Route::post('/giang-vien/do-an/{id}/cham-diem', [GiangVienController::class, 'chamDiem'])
         ->name('giangvien.chamdiem');

    // Đăng nhận xét (route mới)
    Route::post('/giang-vien/do-an/{id}/nhan-xet', [GiangVienController::class, 'dangNhanXet'])
         ->name('giangvien.nhanxet');

    // Xử lý yêu cầu (Gia hạn / Phúc khảo)
    Route::post('/giang-vien/yeu-cau/{id}/xu-ly', [GiangVienController::class, 'xuLyYeuCau'])
         ->name('giangvien.xulyyeucau');
});


// === BẮT ĐẦU GIAI ĐOẠN 10: ROUTES CỦA ADMIN ===
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Trang chính (Danh sách Users)
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
         ->name('admin.dashboard');

    // Trang Sửa User
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])
         ->name('admin.users.edit');

    // Xử lý Cập nhật User
    Route::patch('/admin/users/{user}', [AdminController::class, 'update'])
         ->name('admin.users.update');

    // Xử lý Xóa User
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])
         ->name('admin.users.destroy');

    // Task 3 (Quản lý Thời gian)
    Route::get('/admin/settings', [SettingController::class, 'index'])
         ->name('admin.settings.index');
    Route::post('/admin/settings', [SettingController::class, 'store'])
         ->name('admin.settings.store');
});
// === KẾT THÚC ROUTES CỦA ADMIN ===


// File routes xác thực mặc định của Breeze
require __DIR__.'/auth.php';
use App\Http\Controllers\PlantController;

Route::get('/plant-check', [PlantController::class, 'index'])->name('plant.index');
Route::post('/plant-check', [PlantController::class, 'detectDisease'])->name('plant.detect');
use App\Models\ScanLog;

Route::get('/logs', function () {
    // Lấy danh sách kết quả, mới nhất lên đầu
    $logs = ScanLog::orderBy('created_at', 'desc')->paginate(10);
    return view('scan_logs', ['logs' => $logs]);
})->name('logs.index');