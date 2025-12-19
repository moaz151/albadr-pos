<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::updateOrCreate([ 'name' => 'admin'], [
            'group_name' => 'web',
            'display_name' => 'Administrator',
        ]);

        // $this->manualCreatePermissions($role);
        $this->autoCreatePermissions($role);

        
    }

    private function manualCreatePermissions($role): void
    {
        $permissions = [
            [
                'name' =>'users-create',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'Create User',
            ],
            [
                'name' =>'users-edit',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'Edit User',
            ],
            [
                'name' =>'users-delete',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'Delete User',
            ],
            [
                'name' =>'users-view',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'View User',
            ],
            [
                'name' =>'users-list',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'List Users',
            ],
            [
                'name' => 'items-create',
                'guard_name' => 'web',
                'group_name' => 'Item',
                'display_name' => 'Create Item',
            ],
            [
                'name' => 'items-edit',
                'guard_name' => 'web',
                'group_name' => 'Item',
                'display_name' => 'Edit Item',
            ],
            [
                'name' => 'items-delete',
                'guard_name' => 'web',
                'group_name' => 'Item',
                'display_name' => 'Delete Item',
            ],
            [
                'name' => 'items-view',
                'guard_name' => 'web',
                'group_name' => 'Item',
                'display_name' => 'View Item',
            ],
            [
                'name' => 'items-list',
                'guard_name' => 'web',
                'group_name' => 'Item',
                'display_name' => 'List Items',
            ],
        ];

        foreach ($permissions as $permission) {
            $perm =  Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['group_name' => $permission['group_name'], 'display_name' => $permission['display_name']]
            );
            $role->givePermissionTo($perm);
        }
    }

    private function autoCreatePermissions($role): void
    {
        $modelFiles = Storage::disk('app')->files('Models');
        foreach ($modelFiles as $modelFile) {
            $model = str_replace(['.php', 'Models/'], '', $modelFile); // Sale.php => Sale
            $crudActions = ['create', 'edit', 'delete', 'view', 'list'];
            foreach($crudActions as $action)
            {
                $permission = Permission::updateOrCreate(
                    ['name' => $action . '-' . $model, 'guard_name' => 'web'],
                    [
                        'group_name' => $model,
                        'display_name' => $action . ' ' . $model,
                    ]
                );
                $role->givePermissionTo($permission);
            }
            
        }
    }
}
