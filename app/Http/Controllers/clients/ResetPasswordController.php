<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ResetPasswordController extends Controller
{
    // Hiển thị form reset password
    public function showResetForm($token, Request $request)
    {
        $title = 'Đặt lại mật khẩu';

        return view('clients.auth.reset-password', [
            'title' => $title,
            'token' => $token,
            'email' => $request->email
        ]);
    }



public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $reset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$reset || !Hash::check($request->token, $reset->token)) {
        return back()->withErrors([
            'email' => 'Token không hợp lệ hoặc đã hết hạn'
        ]);
    }

    DB::table('tbt_users')
        ->where('email', $request->email)
        ->update([
            'password' => Hash::make($request->password),
            'updateDate' => now()
        ]);

    DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->delete();

    return redirect()->route('login')
        ->with('status', 'Đặt lại mật khẩu thành công 🎉');
}
}