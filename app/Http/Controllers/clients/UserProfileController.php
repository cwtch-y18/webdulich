<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function __construct()
    {
        parent::__construct(); // Gọi constructor của Controller để khởi tạo $user
    }
    public function index()
    {
        $title = 'Thông tin cá nhân';
        // $username = session()->get('username');
        $userId = $this->getUserId();
        $user = $this->user->getUser($userId);
        return view('clients.user_profile', compact('title','user'));
    }
    public function update(Request $req)
    {
        $fullName = $req->fullName;
        $address = $req->address;
        $email = $req->email;
        $phone = $req->phone;

        $dataUpdate = [
            'fullName' => $fullName,
            'address' => $address,
            'email' => $email,
            'phoneNumber' => $phone
        ];

        $username = session()->get('username');
        $userId = $this->user->getUserId($username);

        $update = $this->user->updateUser($userId, $dataUpdate);
        if (!$update) {
            return response()->json(['error' => true, 'message' => 'Bạn chưa thay đổi thông tin nào, vui lòng kiểm tra lại!']);
        }
        return response()->json(['success' => true, 'message' => 'Cập nhật thông tin thành công!']);
    }
        public function changePassword(Request $req)
    {
        $username = session()->get('username');
        $userId = $this->user->getUserId($username);
        $user = $this->user->getUser($userId);

        if (bcrypt($req->oldPass) === $user->password) {
            $update = $this->user->updateUser($userId, ['password' => bcrypt($req->newPass)]);
            if (!$update) {
                return response()->json(['error' => true, 'message' => 'Mật khẩu mới trùng với mật khẩu cũ!']);
            } else {
                return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);

            }
        } else {
            return response()->json(['error' => true, 'message' => 'Mật khẩu cũ không chính xác.']);
        }
        
    }

public function changeAvatar(Request $req)
{
    $username = session()->get('username');
    $userId = $this->user->getUserId($username);

    $req->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    // Lấy user
    $user = $this->user->getUser($userId);

    // ❌ XÓA AVATAR CŨ (nếu có)
    if ($user && $user->avatar) {
        Storage::disk('public')->delete($user->avatar);
    }

    // ✅ LƯU AVATAR MỚI → trả về path
    $path = $req->file('avatar')->store('avatars', 'public');
    // ví dụ: avatars/1718700000.jpg

    // ✅ LƯU PATH VÀO DB
    $this->user->updateUser($userId, [
        'avatar' => $path
    ]);

    session()->put('avatar', $path);

    return response()->json([
        'success' => true,
        'message' => 'Cập nhật ảnh đại diện thành công!'
    ]);
}
};