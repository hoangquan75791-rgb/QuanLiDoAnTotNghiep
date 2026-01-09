<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập VÀ có vai_tro là 'quan_tri_vien' hay không
        if (!Auth::check() || Auth::user()->vai_tro !== 'quan_tri_vien') {
            // Nếu không phải, ném lỗi 403 (Forbidden - Cấm truy cập)
            abort(403, 'BẠN KHÔNG CÓ QUYỀN TRUY CẬP TRANG NÀY.');
        }

        // Nếu đúng là Admin, cho phép request tiếp tục
        return $next($request);
    }
}
