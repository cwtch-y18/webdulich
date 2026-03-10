<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbt_users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'fullName',
        'email',
        'address',
        'avatar',
        'role_id',
        'isActive',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
    /* ================= PHÂN QUYỀN ================= */

    public function getPermissions()
    {
        return DB::table('tbl_role_permissions')
            ->join('tbl_permissions', 'tbl_permissions.id', '=', 'tbl_role_permissions.permission_id')
            ->where('tbl_role_permissions.role_id', $this->role_id)
            ->pluck('tbl_permissions.name')
            ->toArray();
    }

   public function hasPermission($permission)
{
    return in_array($permission, $this->getPermissions());
}
}