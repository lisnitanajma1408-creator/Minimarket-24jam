<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Sembako', 'slug' => 'sembako'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Makanan Ringan', 'slug' => 'makanan-ringan'],
            ['name' => 'Kebutuhan Rumah Tangga', 'slug' => 'kebutuhan-rumah-tangga'],
            ['name' => 'Perawatan Diri', 'slug' => 'perawatan-diri'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }

        User::updateOrCreate(
            ['email' => 'admin@minimarket24.com'],
            [
                'name' => 'Admin Minimarket',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]
        );
    }
}