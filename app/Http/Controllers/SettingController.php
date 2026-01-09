<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class SettingController extends Controller
{
    /**
     * Hiển thị trang cài đặt thời gian.
     */
    public function index(): View
    {
        // Lấy các cài đặt từ CSDL.
        // firstOrCreate() sẽ tạo ra cài đặt với giá trị null nếu nó chưa tồn tại.
        $registration_deadline = Setting::firstOrCreate(
            ['key' => 'registration_deadline'],
            ['value' => null]
        );

        $submission_deadline = Setting::firstOrCreate(
            ['key' => 'submission_deadline'],
            ['value' => null]
        );

        return view('admin.settings.index', [
            // Chuyển đổi định dạng datetime-local cho input HTML (Y-m-d\TH:i)
            'registration_deadline' => $registration_deadline->value ? Carbon::parse($registration_deadline->value)->format('Y-m-d\TH:i') : null,
            'submission_deadline' => $submission_deadline->value ? Carbon::parse($submission_deadline->value)->format('Y-m-d\TH:i') : null,
        ]);
    }

    /**
     * Cập nhật các cài đặt thời gian.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Xác thực dữ liệu
        $request->validate([
            'registration_deadline' => 'nullable|date',
            // Nếu có nhập registration_deadline thì submission_deadline phải sau đó
            'submission_deadline' => 'nullable|date|after_or_equal:registration_deadline',
        ]);

        // 2. Cập nhật hoặc Tạo (UpdateOrCreate) cài đặt
        Setting::updateOrCreate(
            ['key' => 'registration_deadline'],
            ['value' => $request->registration_deadline]
        );

        Setting::updateOrCreate(
            ['key' => 'submission_deadline'],
            ['value' => $request->submission_deadline]
        );

        // 3. Chuyển hướng lại
        return Redirect::route('admin.settings.index')->with('status', 'settings-updated');
    }
}
