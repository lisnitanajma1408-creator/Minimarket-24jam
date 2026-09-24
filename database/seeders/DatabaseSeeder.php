<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::create(['name' => 'Sembako', 'slug' => 'sembako']);
        Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        Category::create(['name' => 'Makanan Ringan', 'slug' => 'makanan-ringan']);
        Category::create(['name' => 'Kebutuhan Rumah Tangga', 'slug' => 'kebutuhan-rumah-tangga']);
        Category::create(['name' => 'Perawatan Diri', 'slug' => 'perawatan-diri']);

        User::create([
            'name' => 'Admin Minimarket',
            'email' => 'admin@minimarket24.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}