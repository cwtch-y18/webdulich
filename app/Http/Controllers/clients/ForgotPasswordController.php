<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        $title = 'Quên mật khẩu';
        return view('clients.auth.forgot-password', compact('title'));
    }

public function sendResetlink(Request $request)
{
    $request->validate(
        [
            'email' => 'required|email|exists:tbt_users,email',
        ],
        [
            'email.required' => 'Vui lòng nhập email',
            'email.email'    => 'Email không đúng định dạng',
            'email.exists'   => 'Email không tồn tại trong hệ thống',
        ]
    );

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn')
        : back()->withErrors([
            'email' => __($status),
        ]);
}
}