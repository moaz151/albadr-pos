<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Sale;
use App\Enums\OrderStatusEnum;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            OrderStatusEnum::confirmed->value,
            OrderStatusEnum::processing->value,
            OrderStatusEnum::shipped->value,
            OrderStatusEnum::delivered->value,
        ];
        
        $price = $this->faker->randomFloat(2, 10, 1000);
        $shippingCost = $this->faker->randomFloat(2, 5, 100);
        
        return [
            'client_id' => Client::factory(),
            'order_number' => 'ORD-' . strtoupper($this->faker->unique()->bothify('????')) . '-' . time(),
            'status' => $this->faker->randomElement($statuses),
            'payment_method' => 1,
            'price' => $price,
            'shipping_cost' => $shippingCost,
            'total_price' => $price + $shippingCost,
            'shipping_name' => $this->faker->name(),
            'shipping_address' => $this->faker->address(),
            'shipping_phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->optional()->sentence(),
            'sale_id' => null, // Orders don't always have a sale_id initially
        ];
    }
}
