<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin_permissions = Permission::all();

        $admin_permissions = Permission::whereIn('name',[
            'permission_edit',
            'permission_view',
            'permission_access',
            'role_create',
            'role_edit',
            'role_delete',
            'role_view',
            'role_access',
            'user_create',
            'user_edit',
            'user_delete',
            'user_view',
            'user_access',
        ])->get();

        //add here the other permissions to admin for access

        Role::findOrFail(1)->permissions()->sync($superadmin_permissions);
        Role::findOrFail(2)->permissions()->sync($admin_permissions);
    }
}
