<?php

namespace Database\Seeders;
use App\Models\User;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::UpdateOrcreate(
        [
            'username' => 'admin',
        ],
        
        [
            'username' => 'admin',
            'password' => bcrypt('123123'), // Make sure to hash the password
            'full_name' => 'Administrator',
            'status' => UserStatusEnum::active->value
        ]);

        User::factory(50)->create();
    }
}
