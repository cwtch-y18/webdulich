<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'tbt_users';

    public function getAllUsers()
    {
        return DB::table($this->table)->get()->whereIn('role_id', [2, 3]); // Lấy tất cả người dùng có role_id = 3 (user)
    }


    public function upgradeToStaff($userId)
{
    return DB::table('tbt_users')
        ->where('id', $userId)
        ->update([
            'isActive' => 'y',
            'role_id'  => 2   // 🔥 STAFF
        ]);
}
    public function changeStatus($id, $data){
        return DB::table($this->table)
            ->where('id', $id) 
            ->whereIn('role_id', [2, 3])
            ->update($data); 
    }


}
