<?php

namespace Database\Seeders;

use App\Models\Role;
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
        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        // Assign roles
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole  = Role::where('name', 'User')->first();

        $admin->roles()->sync([$adminRole->id]);
        $user->roles()->sync([$userRole->id]);

        // 🔥 Tambah 20 users random
        User::factory(20)->create()->each(function ($u) use ($adminRole, $userRole) {
            // Assign random role
            $role = fake()->randomElement([$adminRole, $userRole]);
            $u->roles()->sync([$role->id]);
        });
    }
}
