<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a fake phone number
        $phoneNumber = $this->faker->numerify('+38#########');

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => strval(phone($phoneNumber, 'UA')->formatE164()),
        ];
    }
}
