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
        // Tài khoản SieuSuperAdmin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'sieusuperadmin',
                'password' => bcrypt('123456'),
                'role' => 'sieusuperadmin',
            ]
        );

        // Tài khoản SuperAdmin 1
        User::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'name' => 'superadmin_1',
                'password' => bcrypt('123456'),
                'role' => 'superadmin_1',
            ]
        );

        // Tạo user thường
        User::updateOrCreate(
            ['email' => 'user@dungthu.com'],
            [
                'name' => 'User Test',
                'password' => bcrypt('user123'),
                'role' => 'user',
            ]
        );

        $this->call([
            ProductSeeder::class,
            BlogSeeder::class,
            SeoKeywordSeeder::class,
            BlogTopicSeeder::class,
        ]);
    }
}
