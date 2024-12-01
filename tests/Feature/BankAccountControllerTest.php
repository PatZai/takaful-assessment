<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BankAccountControllerTest extends TestCase
{
    use RefreshDatabase;

    // Create a test user with a bank account
    private function createUserWithBankAccount()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $account = BankAccount::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000.00,
        ]);

        return [$user, $account];
    }

    // Test deposit method
    public function test_deposit()
    {
        [$user, $account] = $this->createUserWithBankAccount();

        $response = $this->actingAs($user)
            ->postJson('/bank-accounts/' . $account->id . '/deposit', ['amount' => 100.00]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Deposit successful.',
                'balance' => 1100.00, // Check that balance is updated
            ]);
    }

    // Test withdraw method
    public function test_withdraw()
    {
        [$user, $account] = $this->createUserWithBankAccount();

        $response = $this->actingAs($user)
            ->postJson('/bank-accounts/' . $account->id . '/withdraw', ['amount' => 100.00]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Withdrawal successful.',
                'balance' => 900.00, // Check that balance is updated
            ]);
    }

    // Test get balance method
    public function test_get_balance()
    {
        [$user, $account] = $this->createUserWithBankAccount();

        $response = $this->actingAs($user)
            ->getJson('/bank-accounts/' . $account->id . '/balance');

        $response->assertStatus(200)
            ->assertJson([
                'balance' => 1000.00, // Check the balance returned
            ]);
    }

    // Test transfer method
    public function test_transfer()
    {
        [$user, $fromAccount] = $this->createUserWithBankAccount();
        $toAccount = BankAccount::factory()->create(['user_id' => $user->id, 'balance' => 500.00]);

        $response = $this->actingAs($user)
            ->postJson('/transfer', [
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'amount' => 100.00
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Transfer successful.',
            ]);

        // Check if balances were updated correctly
        $fromAccount->refresh();
        $toAccount->refresh();

        $this->assertEquals(900.00, $fromAccount->balance); // Check if balance decreased from the "from" account
        $this->assertEquals(600.00, $toAccount->balance); // Check if balance increased in the "to" account
    }

    // Test error handling (example: invalid deposit amount)
    public function test_invalid_deposit()
    {
        [$user, $account] = $this->createUserWithBankAccount();

        $response = $this->actingAs($user)
            ->postJson('/bank-accounts/' . $account->id . '/deposit', ['amount' => -100.00]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The amount field must be at least 0.01.',
                'errors' => [
                    'amount' => [
                        'The amount field must be at least 0.01.'
                    ],
                ],
            ]);
    }
}
