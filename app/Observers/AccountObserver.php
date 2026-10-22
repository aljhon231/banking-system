<?php

namespace App\Observers;

use App\Models\Account;
use App\Notifications\LowBalanceNotification;

class AccountObserver
{
    public function updated(Account $account): void
    {
        if (!$account->isDirty('balance')) {
            return;
        }

        $oldBalance = (float) $account->getOriginal('balance');
        $newBalance = (float) $account->balance;
        $minimumBalance = (float) $account->minimum_balance;

        // Notify only when the balance crosses the minimum threshold.
        if (
            $oldBalance > $minimumBalance &&
            $newBalance <= $minimumBalance &&
            $account->user
        ) {
            $account->user->notify(
                new LowBalanceNotification($account)
            );
        }
    }
}