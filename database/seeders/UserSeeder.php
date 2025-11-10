<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            "name" => "Admin",
            "email" => "pn.tanahgrogot@gmail.com",
            "password" => Hash::make("pnkelas2"),
            "email_verified_at" => now(),
        ]);

        // Create test user for Kesekretariatan
        User::create([
            "name" => "User Kesekretariatan",
            "email" => "kesekretariatan@portal.com",
            "password" => Hash::make("password"),
            "email_verified_at" => now(),
        ]);

        // Create test user for Kepaniteraan
        User::create([
            "name" => "User Kepaniteraan",
            "email" => "kepaniteraan@portal.com",
            "password" => Hash::make("password"),
            "email_verified_at" => now(),
        ]);
    }
}
