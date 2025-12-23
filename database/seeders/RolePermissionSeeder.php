<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage loads',
            'view loads',
            'assign drivers',
            'update load status',
            'upload documents',
            'view documents',
            'manage users',
            'view map',
            'track location',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $dispatcherRole = Role::create(['name' => 'Dispatcher']);
        $dispatcherRole->givePermissionTo([
            'manage loads',
            'view loads',
            'assign drivers',
            'update load status',
            'upload documents',
            'view documents',
            'view map',
        ]);

        $driverRole = Role::create(['name' => 'Driver']);
        $driverRole->givePermissionTo([
            'view loads',
            'update load status',
            'upload documents',
            'view documents',
            'track location',
        ]);

        // Create users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        $dispatcher = User::create([
            'name' => 'Dispatcher User',
            'email' => 'dispatcher@example.com',
            'password' => Hash::make('password'),
        ]);
        $dispatcher->assignRole('Dispatcher');

        $driver = User::create([
            'name' => 'Driver User',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
        ]);
        $driver->assignRole('Driver');
    }
}
