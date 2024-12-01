<?php

namespace App\Models;

class SavingsAccount extends BankAccount
{
    public function withdraw($amount)
    {
        if ($this->balance - $amount < $this->min_balance) {
            throw new \Exception('Cannot withdraw; balance must remain above minimum threshold.');
        }
        parent::withdraw($amount);
    }
}
