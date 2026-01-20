<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Role;

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
        $adminRole = Role::where('name', 'admin')->first();
        $super = Role::where('name', 'super_admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "Regular User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'is_vip' => false,
                'role_id' => $userRole->id ?? null, // Uses null if user role doesn't exist
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Admin $i",
                'email' => "admin$i@gmail.com",
                'password' => Hash::make('password'),
                'is_vip' => true,
                'vip_expires_at' => now()->addYear(),
                'role_id' => $adminRole->id,
            ]);
        }

        User::create([
            'name' => 'Super Admin',
            'email' => 'test@gmail.com',
            'password' => Hash::make('12345678'),
            'is_vip' => true,
            'role_id' => $super->id,
        ]);
    }
}
