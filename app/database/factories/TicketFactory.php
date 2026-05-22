<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::inRandomOrder()->first()->id ?? 1,
            'text' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['new', 'in process', 'processed']),
            'created_at' => now()->subDays(rand(1, 30)),
        ];
    }
}
