<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo 5 người dùng giả ngẫu nhiên
        User::factory()->count(5)->create();

        // Tạo một người dùng với vai trò 'super_admin' và mật khẩu là '123'
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'role' => 'super_admin',
            'status' => 'verified',
            'password' => Hash::make('123'), // Mã hóa mật khẩu
        ]);

        // Tạo một người dùng với vai trò 'admin' và mật khẩu là '123'
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'status' => 'verified',
            'password' => Hash::make('123'), // Mã hóa mật khẩu
        ]);

        // Tạo một người dùng với vai trò 'user' và mật khẩu là '123'
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'status' => 'verified',
            'password' => Hash::make('123'), // Mã hóa mật khẩu
        ]);
    }
}
