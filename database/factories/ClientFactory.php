<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ClientStatusEnum;
use App\Enums\ClientRegistrationEnum;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'balance' => 0,
            'status' => $this->faker->randomElement([ClientStatusEnum::active, ClientStatusEnum::inactive]),
            'registered_via' => $this->faker->randomElement([ClientRegistrationEnum::pos, ClientRegistrationEnum::app]),
        ];
    }
}
