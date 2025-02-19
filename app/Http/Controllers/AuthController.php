<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        // Xác thực CAPTCHA
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Vui lòng xác minh bạn không phải là robot.',
            'g-recaptcha-response.captcha' => 'CAPTCHA không hợp lệ.',
        ]);
        
        $validated = $request->validated();

        // Tạo người dùng mới
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'unverified',
            'verification_token' => sha1(time()),
        ]);

        // Gửi email xác nhận
        Mail::to($user->email)->send(new WelcomeMail($user));

        // Chuyển đến view thông báo đã gửi email kích hoạt tài khoản
        return view('auth.success')->with('message', 'Đã gửi email xác nhận. Vui lòng kiểm tra email của bạn để kích hoạt tài khoản.');
    }
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if ($user) {
            $user->status = 'verified';
            $user->verification_token = null;
            $user->save();
            Auth::login($user);
            return redirect()->route('login')->with('success', 'Tài khoản của bạn đã được xác nhận thành công. Bạn có thể đăng nhập ngay.');
        }
        return redirect()->route('login')->withErrors(['error' => 'Liên kết xác nhận không hợp lệ.']);
    }
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin' || $user->role === 'super_admin') {
                return redirect()->route('dashboard');
            }
    
            return redirect()->route('index');
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không đúng.'])->withInput();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    public function showForgotForm()
    {
        return view('forgot_password');
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $token = sha1(time());
        $user->verification_token = $token;
        $user->save();

        Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

        return back()->with('success', 'Một liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
    }
    public function showResetForm($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Liên kết đặt lại mật khẩu không hợp lệ.']);
        }

        return view('reset', ['token' => $token]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Za-z]/',
                'regex:/[\W_]/',
                'confirmed',
            ],
            'token' => 'required',
        ], [
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải có ít nhất một chữ cái và một ký tự đặc biệt.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = User::where('verification_token', $request->token)->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Liên kết đặt lại mật khẩu không hợp lệ.']);
        }

        $user->password = Hash::make($request->password);
        $user->verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
