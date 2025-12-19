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

        $this->autoCreatePermissions($role);

        
    }

    private function autoCreatePermissions($role): void
    {
        $modelFiles = Storage::disk('app')->files('Models');
        foreach ($modelFiles as $modelFile) {
            $model = str_replace(['.php', 'Models/'], '', $modelFile); // Sale.php => Sale
            if ($model == 'File' || $model == 'Cart' || $model == 'CartItem' || $model == 'orderItem') {
                continue;
            }
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

        // Manually ensure permissions for non-model features (e.g., Roles management, Reports pages)
        $extraPermissions = [
            [
                'name' => 'list-Role',
                'group_name' => 'Role',
                'display_name' => 'List Role',
            ],
            [
                'name' => 'create-Role',
                'group_name' => 'Role',
                'display_name' => 'Create Role',
            ],
            [
                'name' => 'edit-Role',
                'group_name' => 'Role',
                'display_name' => 'Edit Role',
            ],
            [
                'name' => 'delete-Role',
                'group_name' => 'Role',
                'display_name' => 'Delete Role',
            ],
            [
                'name' => 'view-Role',
                'group_name' => 'Role',
                'display_name' => 'View Role',
            ],
            [
                'name' => 'list-Report',
                'group_name' => 'Report',
                'display_name' => 'List Report',
            ],
            [
                'name' => 'view-reports',
                'group_name' => 'Report',
                'display_name' => 'View Reports',
            ],
            [
                'name' => 'manage-settings',
                'group_name' => 'Setting',
                'display_name' => 'Manage Settings',
            ],
        ];

        foreach ($extraPermissions as $permission) {
            $perm = Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['group_name' => $permission['group_name'], 'display_name' => $permission['display_name']]
            );
            $role->givePermissionTo($perm);
        }
    }
}
