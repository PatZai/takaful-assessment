<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BankAccount;
use App\Models\Transaction;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Create users with bank accounts and transactions
        User::factory()
            ->count(10) // Create 10 users
            ->has(
                BankAccount::factory() // Each user will have 3 bank accounts
                    ->count(3)
                    ->hasTransactions(5) // Each bank account will have 5 transactions
            )
            ->create();

        // Optionally, you can also directly create data for other models as needed
        // For example:
        // Transaction::factory()->count(50)->create(); // Create 50 transactions
    }
}
