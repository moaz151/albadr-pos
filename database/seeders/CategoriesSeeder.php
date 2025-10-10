<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Enums\CatStatusEnum;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Category::updateOrCreate([
                'name' => 'Category ' . $i,
                ],
                [
                    'name' => 'Category ' . $i,
                    'status' => CatStatusEnum::active,
                ]);
        }
    }
}
