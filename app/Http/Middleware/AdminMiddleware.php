<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập vào trang này.');
        }

        return $next($request);
    }
}
