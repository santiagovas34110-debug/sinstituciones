<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'santiagovas34110@gmail.com',
        //     'password' => Hash::make('1996'), // 🔑 contraseña
        // ]);
        User::create([
            'name' => 'Anyelber',
            'email' => 'anyslehider@gmail.com',
            'password' => Hash::make('Sac1231!'), // 🔑 contraseña
        ]);
    }
}