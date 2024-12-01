<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BankAccount;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition()
    {
        return [
            'account_id' => BankAccount::factory(), 
            'type' => $this->faker->randomElement(['deposit', 'withdrawal', 'transfer']),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'details' => $this->faker->sentence(),
        ];
    }
}
