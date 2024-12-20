<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Kiểm tra vai trò của người dùng
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Chỉ SuperAdmin mới có quyền truy cập vào trang này.');
        }

        return $next($request);
    }
}
