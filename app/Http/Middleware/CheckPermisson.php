<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermisson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user= Auth::guard('admin')->user();
        // Kiểm tra nếu người dùng không có quyền truy cập
        if (!$user || !$user->hasPermission('manage_user')) { // Thay 'permission_name' bằng tên quyền bạn muốn kiểm tra
            // Chuyển hướng hoặc trả về lỗi 403
            toastr()->error('Bạn không có quyền truy cập .Vui lòng đăng nhập vào Admin để thực hiện.');
            return redirect()->route('admin.dashboard');
        }
        
        return $next($request);
    }
}
;