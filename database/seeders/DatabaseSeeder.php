<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo admin account
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@dungthu.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Tạo user thường
        User::factory()->create([
            'name' => 'User Test',
            'email' => 'user@dungthu.com',
            'password' => bcrypt('user123'),
            'role' => 'user',
        ]);

        $this->call([
            ProductSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
