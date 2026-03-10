<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    private $admin;

    public function __construct()
    {
        $this->admin = new Admin();
    }
    public function index()
    {
        $title = 'Quản lý Admin';
        $admin = Auth::guard('admin')->user();
        return view('admin.profile-admin', compact('title', 'admin'));
    }

 public function updateAdmin(Request $request)
{
    $admin = Auth::guard('admin')->user(); 
    $dataUpdate = [
        'fullName'   => $request->fullName,
        'email'      => $request->email,
        'address'    => $request->address,
        'updateDate' => now(),
    ];

    // Nếu có nhập mật khẩu mới
    if ($request->password) {
        $dataUpdate['password'] = Hash::make($request->password);
    }

    $update = Admin::where('id', $admin->id)->update($dataUpdate);

    if ($update) {
        return response()->json([
            'success' => true,
            'data'    => Admin::find($admin->id)
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Không có thông tin nào thay đổi!'
    ]);
}


public function updateAvatar(Request $req)
{
    if (!$req->hasFile('avatarAdmin')) {
        return response()->json(['error' => true, 'message' => 'Không có file']);
    }

    $avatar = $req->file('avatarAdmin');

    $filename = 'admin_' . time() . '.' . $avatar->getClientOriginalExtension();

    $path = $avatar->move(
        public_path('admin/assets/images/user-profile'),
        $filename
    );

    // Lưu tên file vào DB
    auth('admin')->user()->update([
        'avatar' => $filename
    ]);

    return response()->json([
        'success' => true,
        'avatar' => asset('admin/assets/images/user-profile/' . $filename)
    ]);
}

}
