<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a File Admin user
        User::create([
            'name' => 'Nazrie',
            'email' => 'admin@kbss.gov.my',
            'password' => Hash::make('password'),
            'role' => 'File Admin',
        ]);

        // Create a File Officer user
        User::create([
            'name' => 'Zikri',
            'email' => 'officer@kbss.gov.my',
            'password' => Hash::make('password'),
            'role' => 'File Officer',
        ]);
    }
}
