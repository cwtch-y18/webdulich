<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\LoginModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginAdminController extends Controller
{

    private $login;

    public function __construct()
    {
        $this->login = new LoginModel();
    }
    public function index()
    {
        $title = 'Đăng nhập';
        

        return view('admin.login', compact('title'));
    }

public function loginAdmin(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    if (!Auth::guard('admin')->attempt([
        'username' => $request->username,
        'password' => $request->password,
        'isActive' => 'Y',
    ])) {
        toastr()->error('Sai tài khoản hoặc mật khẩu');
        return back();
    }

    $request->session()->regenerate();

    toastr()->success('Đăng nhập thành công');
    return redirect()->route('admin.dashboard');
}
public function logout(Request $request)
{
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login');
}
}
