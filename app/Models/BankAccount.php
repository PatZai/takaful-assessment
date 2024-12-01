<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'balance', 'min_balance'];

    // Deposit money
    public function deposit($amount)
    {
        $this->balance += $amount;
        $this->save();

        // Log the transaction
        $this->logTransaction('deposit', $amount);
    }

    // Withdraw money
    public function withdraw($amount)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient funds.');
        }
        $this->balance -= $amount;
        $this->save();

        // Log the transaction
        $this->logTransaction('withdrawal', $amount);
    }

    // Get balance
    public function getBalance()
    {
        return $this->balance;
    }

    // Log transactions
    protected function logTransaction($type, $amount, $details = null)
    {
        $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'details' => $details,
        ]);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    public function accountHolder()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
