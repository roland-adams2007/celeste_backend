<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Roland',
            'last_name' => 'Adams',
            'email' => 'admin@celeste.com',
            'phone_number' => '+2347043507082',
            'password' => Hash::make('admin'),
            'is_admin' => true,
            'is_active' => true
        ]);
    }
}
