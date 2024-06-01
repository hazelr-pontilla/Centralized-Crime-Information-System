<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'permission_create',
            ],
            [
                'name' => 'permission_edit',
            ],
            [
                'name' => 'permission_delete',
            ],
            [
                'name' => 'permission_view',
            ],
            [
                'name' => 'permission_access',
            ],

            [
                'name' => 'role_create',
            ],
            [
                'name' => 'role_edit',
            ],
            [
                'name' => 'role_delete',
            ],
            [
                'name' => 'role_view',
            ],
            [
                'name' => 'role_access',
            ],

            [
                'name' => 'entry_create',
            ],
            [
                'name' => 'entry_edit',
            ],
            [
                'name' => 'entry_delete',
            ],
            [
                'name' => 'entry_view',
            ],
            [
                'name' => 'entry_access',
            ],

            [
                'name' => 'entry_restore',

            ],

            [
                'name' => 'suspect_create',
            ],
            [
                'name' => 'suspect_edit',
            ],
            [
                'name' => 'suspect_delete',
            ],
            [
                'name' => 'suspect_view',
            ],
            [
                'name' => 'suspect_access',
            ],

            [
                'name' => 'victim_create',
            ],
            [
                'name' => 'victim_edit',
            ],
            [
                'name' => 'victim_delete',
            ],
            [
                'name' => 'victim_view',
            ],
            [
                'name' => 'victim_access',
            ],

            [
                'name' => 'children_create',
            ],
            [
                'name' => 'children_edit',
            ],
            [
                'name' => 'children_delete',
            ],
            [
                'name' => 'children_view',
            ],
            [
                'name' => 'children_access',
            ],

            [
                'name' => 'user_create',
            ],
            [
                'name' => 'user_edit',
            ],
            [
                'name' => 'user_delete',
            ],
            [
                'name' => 'user_view',
            ],
            [
                'name' => 'user_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
