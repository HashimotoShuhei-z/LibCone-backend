<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 一般ユーザーの作成
        User::create([
            'name' => 'user1',
            'email' => 'test-user@example.com',
            'password' => Hash::make('password123'),
            'gender_id' => 0,
            'age' => 30,
            'type_id' => 0,
            'user_icon' => 'default_icon.png',
            'company_id' => 1,
            'month_point' => 3000,
            'special_point' => 300,
        ]);

        // 管理者の作成
        User::create([
            'name' => 'admin',
            'email' => 'test-admin@example.com',
            'password' => Hash::make('password123'),
            'gender_id' => 1,
            'age' => 40,
            'type_id' => 1,
            'user_icon' => 'default_icon.png',
            'company_id' => 1,
        ]);
    }
}
