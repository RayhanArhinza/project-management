<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Rayhan Saneval',
                'email' => 'rayhan@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Hanza Putra',
                'email' => 'hanza@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Siti Aisyah',
                'email' => 'aisyah@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Indah Pratiwi',
                'email' => 'indah@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Ahmad Zaki',
                'email' => 'zaki@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
            [
                'name' => 'Lina Marlina',
                'email' => 'lina@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'member_id' => 1,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
