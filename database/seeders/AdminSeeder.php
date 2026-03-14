<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => '超级管理员',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'is_active' => true,
        ]);
    }
}