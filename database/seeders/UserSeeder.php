<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Akun Admin
        User::create([
            'name' => 'Admin Konter',
            'email' => 'admin@konter.com', // Ganti dengan email admin Anda
            'password' => Hash::make('password'), // adminaris123
            'role' => 'admin',
        ]);

        // (Opsional) Buat Akun Kasir untuk contoh
        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@konter.com', // Ganti dengan email kasir
            'password' => Hash::make('password'), // kasirimas123
            'role' => 'kasir',
        ]);
    }
}
