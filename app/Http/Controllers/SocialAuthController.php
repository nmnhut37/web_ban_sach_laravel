<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Nếu người dùng chưa có trong hệ thống, tạo mới
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(24)), // Tạo password ngẫu nhiên
                    'role' => 'user', // Vai trò mặc định là user
                    'status' => 'verified', // Đánh dấu người dùng đã xác minh
                ]);
            }

            // Đăng nhập người dùng
            Auth::login($user);

            return redirect()->route('index'); // Chuyển hướng đến trang sau khi đăng nhập thành công
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Có lỗi xảy ra khi đăng nhập với Google');
        }
    }
}
