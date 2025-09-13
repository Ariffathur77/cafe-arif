<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerRole = Role::firstOrCreate(['name' => 'Owner']);
        $cashierRole = Role::firstOrCreate(['name' => 'Cashier']);

        // Membuat user Owner
        User::firstOrCreate(
            ['email' => 'owner@cafe.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'role_id' => $ownerRole->id,
            ]
        );

        // Membuat user Kasir
        User::firstOrCreate(
            ['email' => 'kasir@cafe.com'],
            [
                'name' => 'Kasir 1',
                'password' => Hash::make('password'),
                'role_id' => $cashierRole->id,
            ]
        );
    }
}
