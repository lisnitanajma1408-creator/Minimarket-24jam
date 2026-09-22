<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Minimarket',
            'email' => 'admin@minimarket24.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}