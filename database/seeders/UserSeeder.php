<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat 1 akun admin
        User::create([
            'name' => 'Admin Membership',
            'email' => 'admin@membership.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
