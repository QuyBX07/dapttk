<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Hash;

class AuthCOntroller extends Controller
{
    public function showLoginForm()
        {
            return view('login_signin.login');
        }

        public function showRegisterForm()
        {
            return view('login_signin.register');
        }

        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');
        
            if (Auth::guard('account')->attempt($credentials)) {
                $request->session()->regenerate();
        
                // Lưu avatar vào session
                $account = Auth::guard('account')->user();
                session([
                    'user_name' => $account->name,
                    'user_email' => $account->email,
                    'user_role' => $account->role,
                ]);
                return redirect()->intended('/');
            }
        
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);
        }

public function register(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:accounts,email',
        'password' => 'required|confirmed|min:6',
    ]);

    $account = new Account();
    $account->id = (string) Str::uuid();
    $account->name = $request->username;
    $account->email = $request->email;
    $account->password = Hash::make($request->password);
    $account->phone = $request->phone;
    $account->role = 'admin';
    $account->save();

    return redirect()->route('register.form')->with('success', 'Đăng ký thành công!');
}

    public function logout(Request $request)
    {
        Auth::logout();  // Đăng xuất người dùng
        $request->session()->invalidate();  // Hủy session
        $request->session()->regenerateToken();  // Regenerate CSRF token

        return redirect('/login');  // Chuyển hướng về trang đăng nhập
    }
}
