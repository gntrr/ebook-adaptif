<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com', // ganti ke email admin dev kamu
            'password' => Hash::make('password123'), // ganti ke password aman
            'is_admin' => true,
        ]);
    }
}
