<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'access admin panel',
            'review companies',
            'manage users',
            'manage companies',
            'manage categories',
            'view audit logs',
            'create trade posts',
            'edit own trade posts',
            'delete own trade posts',
            'moderate trade posts',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $administrator = Role::findOrCreate('administrator', 'web');
        $buyer = Role::findOrCreate('buyer', 'web');
        $seller = Role::findOrCreate('seller', 'web');
        $broker = Role::findOrCreate('broker', 'web');

        $superAdmin->syncPermissions(Permission::all());
        $administrator->syncPermissions(Permission::all());

        $tradePostPermissions = [
            'create trade posts',
            'edit own trade posts',
            'delete own trade posts',
        ];

        $buyer->syncPermissions($tradePostPermissions);
        $seller->syncPermissions($tradePostPermissions);
        $broker->syncPermissions($tradePostPermissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
