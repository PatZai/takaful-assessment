<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccount;
use App\Models\SavingsAccount;

class BankAccountController extends Controller
{
    // Deposit
    public function deposit(Request $request, $accountId)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        DB::beginTransaction(); // Start a transaction

        try {
            $account = BankAccount::findOrFail($accountId);
            $accountHolder = $account->accountHolder; // Access the account holder (User)

            $account->deposit($request->amount);

            DB::commit(); // Commit the transaction

            return response()->json([
                'message' => 'Deposit successful.',
                'account_holder' => $accountHolder->name, // Include the account holder's name in the response
                'balance' => $account->balance,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error

            return response()->json(['error' => 'Failed to process deposit: ' . $e->getMessage()], 400);
        }
    }

    // Withdraw
    public function withdraw(Request $request, $accountId)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        DB::beginTransaction(); // Start a transaction

        try {
            $account = BankAccount::findOrFail($accountId);
            $account->withdraw($request->amount);

            DB::commit(); // Commit the transaction

            return response()->json([
                'message' => 'Withdrawal successful.',
                'balance' => $account->balance,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error

            return response()->json(['error' => 'Failed to process withdrawal: ' . $e->getMessage()], 400);
        }
    }

    // Get balance
    public function getBalance($accountId)
    {
        try {
            $account = BankAccount::findOrFail($accountId);
            return response()->json(['balance' => $account->getBalance()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve balance: ' . $e->getMessage()], 400);
        }
    }

    // Transfer funds
    public function transfer(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|exists:bank_accounts,id',
            'to_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction(); // Start a transaction

        try {
            $fromAccount = BankAccount::findOrFail($request->from_account_id);
            $toAccount = BankAccount::findOrFail($request->to_account_id);

            $fromAccount->withdraw($request->amount);
            $toAccount->deposit($request->amount);

            DB::commit(); // Commit the transaction

            return response()->json(['message' => 'Transfer successful.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error

            return response()->json(['error' => 'Failed to process transfer: ' . $e->getMessage()], 400);
        }
    }
}
