<?php

namespace Database\Seeders;
use App\Models\User;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            [
                'group_name' => 'web',
                'display_name' => 'Administrator',
            ]
        );

        $adminUser = User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'username' => 'admin',
                'password' => bcrypt('123123'), // Make sure to hash the password
                'full_name' => 'Administrator',
                'status' => UserStatusEnum::active->value,
            ]
        );

        if (! $adminUser->hasRole($adminRole->name)) {
            $adminUser->assignRole($adminRole);
        }

        User::factory(5)->create();
    }
}
