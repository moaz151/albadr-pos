<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Warehouse;
use App\Enums\WarehouseStatusEnum;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
            'name' =>$this->faker->word(),
            'description' =>$this->faker->text(),
            'status' =>$this->faker->randomElement([WarehouseStatusEnum::active, WarehouseStatusEnum::inactive]),
        ];
    }
}
