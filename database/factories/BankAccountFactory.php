<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['checking', 'savings']),
            'balance' => $this->faker->randomFloat(2, 100, 10000),
            'min_balance' => $this->faker->optional()->randomFloat(2, 10, 500),
        ];
    }
}
