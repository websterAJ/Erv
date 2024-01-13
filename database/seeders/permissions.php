<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class permissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view-report']);
        Permission::create(['name' => 'create-report']);
        Permission::create(['name' => 'download-report']);

        $role = Role::create(['name' => 'user']);
        $permission= Permission::create(['name' => 'view-generate']);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $role = Role::create(['name' => 'supervisor']);
        $permission=Permission::create(['name' => 'manage-config']);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);
        $permission=Permission::create(['name' => 'manage-config-users']);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $role = Role::create(['name' => 'moderator']);
        $permission= Permission::create(['name' => 'manage-config-permissions']);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo(Permission::all());
    }
}
