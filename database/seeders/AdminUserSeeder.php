<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'role_id'=>1,
        'name' => 'admin',
        'email' => 'admin@yopmail.com',
        'password' => Hash::make('admin@123')
        ]);

        User::create([
        'role_id'=>2,
        'name' => 'user',
        'email' => 'user@yopmail.com',
        'password' => Hash::make('user@123')
        ]);
    }
}
