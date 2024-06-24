<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@mail.com',
            'password'  => Hash::make('admin123'),
        ])->assignRole('admin');

        User::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@mail.com',
            'password'  => Hash::make('qwe132456'),
        ])->assignRole('superadmin');

        User::create([
            'name'      => 'User',
            'email'     => 'user@mail.com',
            'password'  => Hash::make('user123'),
        ])->assignRole('user');
    }
}
