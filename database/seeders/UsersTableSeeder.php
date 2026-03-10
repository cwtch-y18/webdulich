<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tbt_users')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'email' => 'admin@example.com',
                'role_id'  => 1,
                'isActive' => 1,
                'createDate' => now(),
                'updateDate' => now(),
            ],
            [
                'username' => 'user1',
                'password' => Hash::make('123456'),
                'email' => 'user1@example.com',
                'role_id'  => 2,
                'isActive' => 1,
                'createDate' => now(),
                'updateDate' => now(),
            ],
                ['username' => 'staff',
                'password' => Hash::make('123456'),
                'email' => 'staff@example.com',
                'role_id'  => 2,
                'isActive' => 1,
                'createDate' => now(),
                'updateDate' => now(),],
        
        ]);
    }
}