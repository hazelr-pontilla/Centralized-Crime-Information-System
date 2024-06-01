<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                //superadmin
                'name' => 'Superadmin',
                'email' => 'super@admin.com', //emails can be change
                'password' => bcrypt('superadmin'), //passwords can be change
                'remember_token' => null,
            ],

            [
                //admin
                'name' => 'Admin',
                'email' => 'mini@admin.com',
                'password' => bcrypt('admin'),
                'remember_token' => null,
            ],

        ];

        User::insert($users);
    }
}
