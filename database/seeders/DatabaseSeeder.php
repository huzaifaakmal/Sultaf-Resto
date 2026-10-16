<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
<<<<<<< HEAD
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sultaf.com'],
            [
                'name' => 'Admin Sultaf',
                'username' => 'admin',
                'no_hp' => '081234567890',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner@sultaf.com'],
            [
                'name' => 'Owner Sultaf',
                'username' => 'owner',
                'no_hp' => '081234567891',
                'password' => Hash::make('password123'),
                'role' => 'owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@sultaf.com'],
            [
                'name' => 'Kasir Sultaf',
                'username' => 'kasir',
                'no_hp' => '081234567892',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );

        User::firstOrCreate(
            ['email' => 'dapur@sultaf.com'],
            [
                'name' => 'Dapur Sultaf',
                'username' => 'dapur',
                'no_hp' => '081234567893',
                'password' => Hash::make('password123'),
                'role' => 'dapur',
            ]
        );
    }
}
=======

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            AdminUserSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
