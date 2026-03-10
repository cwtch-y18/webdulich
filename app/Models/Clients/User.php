<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, CanResetPasswordTrait, Notifiable;
    protected $table = 'tbt_users'; 
    const CREATED_AT = 'createDate';
    const UPDATED_AT = 'updateDate';
    public function getUserId($username)
    {
        return DB::table($this->table)
            ->select('id')
            ->whereIn('role_id', [2, 3]) // Chỉ lấy id của staff và user
            ->where('username', $username)->value('id');
    }
    public function getUser($id)
    {
        $users = DB::table($this->table)
            ->where('id', $id)
            ->first();

        return $users;
    }
    public function updateUser($id, $data)
    {
        $update = DB::table($this->table)
            ->where('id', $id)
            ->update($data);

        return $update;
    }
        public function getMyTours($id)
    {
        $myTours =  DB::table('tbl_booking')
        ->join('tbl_tours', 'tbl_booking.tourId', '=', 'tbl_tours.tourId')
        ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
        ->where('tbl_booking.id', $id)
        ->orderByDesc('tbl_booking.bookingDate')
        ->take(3)
        ->get();

        foreach ($myTours as $tour) {
            // Lấy rating từ tbl_reviews cho mỗi tour
            $tour->rating = DB::table('tbl_reviews')
                ->where('tourId', $tour->tourId)
                ->where('id', $id)
                ->value('rating'); // Dùng value() để lấy giá trị rating
        }
        foreach ($myTours as $tour) {
            // Lấy danh sách hình ảnh thuộc về tour
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageUrl');
        }

        return $myTours;
    }
}
