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
        Category::UpdateOrcreate([
            'name' => 'Default Category Name'
        ],
        [
            'name' => 'Default Category Name',
            'status' => CatStatusEnum::active->value // or any default status you want
        ]);

        Category::factory(30)->create();
        
    }
}
