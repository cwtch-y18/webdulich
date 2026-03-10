<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\UserModel;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{

    private $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }
    public function index()
    {
        $title = 'Quản lý người dùng';

        $users = $this->users->getAllUsers();

        foreach ($users as $user) {
            if (!$user->fullName) {
                $user->fullName = "Unnamed";
            }
            if (!$user->avatar) {
                $user->avatar = 'unnamed.png';
            }
            if ($user->isActive == 'y')
                $user->isActive = 'Đã kích hoạt';
            else
                $user->isActive = 'Chưa kích hoạt';
        }
        // dd($users);

        return view('admin.users', compact('title', 'users'));
    }

public function activeUser(Request $request)
{
    $userId = $request->userId;

    $updated = $this->users->upgradeToStaff($userId);

    if ($updated) {
        return response()->json([
            'success' => true,
            'message' => 'Đã kích hoạt và nâng thành Nhân viên!'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Có lỗi xảy ra!'
    ], 500);
}

public function changeStatus(Request $request)
{
    $userId = $request->userId;
    $status = $request->status;

    if (!$userId || !$status) {
        return response()->json([
            'success' => false,
            'message' => 'Thiếu dữ liệu!'
        ], 400);
    }

    // map action -> giá trị DB
    switch ($status) {
        case 'b': // chặn
            $dataUpdate = ['status' => 'b'];
            break;

        case 'd': // xóa
            $dataUpdate = ['status' => 'd'];
            break;

        case 'u': // bỏ chặn
        case 'r': // khôi phục
            $dataUpdate = ['status' => null];
            break;

        default:
            return response()->json([
                'success' => false,
                'message' => 'Trạng thái không hợp lệ!'
            ], 400);
    }

    $updated = $this->users->changeStatus($userId, $dataUpdate);

    if ($updated) {
        return response()->json([
            'success' => true,
            'status' => $this->getStatusText($status),
            'message' => 'Cập nhật trạng thái thành công!'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi cập nhật trạng thái!'
    ], 500);
}

    private function getStatusText($status)
    {
        switch ($status) {
            case 'b':
                return 'Đã chặn';
            case 'd':
                return 'Đã xóa';
            case 'r':
                return 'Đã khôi phục';
            case 'u':
                return 'Bỏ chặn';
            default:
                return 'Không xác định';
        }
    }

}
