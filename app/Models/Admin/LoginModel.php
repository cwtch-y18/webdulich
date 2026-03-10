<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginModel extends Model
{
    protected $table = 'tbt_users';

    public function loginAdmin($username, $password)
    {
        $user = DB::table($this->table)
            ->where('username', $username)
            ->where('isActive', 'Y')
            ->whereNull('status')   // không bị ban / delete
            ->first();

        if (!$user) {
            return null;
        }

        // Check password
        if (strlen($user->password) > 32) {
            // bcrypt
            if (!Hash::check($password, $user->password)) {
                return null;
            }
        } else {
            // MD5
            if (md5($password) !== $user->password) {
                return null;
            }

            // auto convert MD5 → bcrypt
            DB::table($this->table)
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($password)
                ]);
        }

        // Check role (1 = admin, 2 = staff)
        if (!in_array($user->role_id, [1, 2])) {
            return null;
        }

        return $user;
    }

}