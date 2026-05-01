<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Guard: never run seeder in production with demo credentials
        if (app()->isProduction()) {
            $this->command->warn('RolesAndPermissionsSeeder skipped in production.');
            return;
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = collect(['super-admin', 'admin', 'agent', 'user'])
            ->mapWithKeys(fn ($name) => [$name => Role::firstOrCreate(['name' => $name])]);

        // Seed demo users — passwords from .env or safe fallback for local only
        $seeds = [
            ['name' => 'Super Jackson',  'email' => 'superadmin@demo.com', 'role' => 'super-admin'],
            ['name' => 'Admin User',     'email' => 'admin@demo.com',      'role' => 'admin'],
            ['name' => 'Agent User',     'email' => 'agent@demo.com',      'role' => 'agent'],
            ['name' => 'Shedrack Bisala','email' => 'bisala@demo.com',     'role' => 'user'],
        ];

        foreach ($seeds as $seed) {
            $user = User::firstOrCreate(
                ['email' => $seed['email']],
                [
                    'name'     => $seed['name'],
                    'password' => Hash::make(
                        env('SEED_PASSWORD', 'Password123!')
                    ),
                ]
            );
            $user->syncRoles($roles[$seed['role']]);
        }
    }
}