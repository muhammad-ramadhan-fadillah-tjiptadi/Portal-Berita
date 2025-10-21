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
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('AdminID'),
                'role' => 'admin',
                'remember_token' => \Illuminate\Support\Str::random(10),
            ]
        );
    }
}
