<?php

namespace App\Observers;

use App\Models\Transaction;
use RuntimeException;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $account = $transaction->account;

        $amount = (float) $transaction->amount;
        $currentBalance = (float) $account->balance;

        /*
         * Deposit:
         * Add the transaction amount to the account balance.
         */
        if ($transaction->type === 'deposit') {

            $account->balance = $currentBalance + $amount;

        /*
         * Withdrawal or Transfer:
         * Subtract the transaction amount from the account balance.
         */
        } elseif (
            $transaction->type === 'withdrawal' ||
            $transaction->type === 'transfer'
        ) {

            $newBalance = $currentBalance - $amount;

            /*
             * Do not allow the account balance
             * to become negative.
             */
            if ($newBalance < 0) {
                throw new RuntimeException(
                    'Insufficient funds. Balance cannot be negative.'
                );
            }

            /*
             * Do not allow the account balance
             * to go below the minimum balance.
             */
            if ($newBalance < (float) $account->minimum_balance) {
                throw new RuntimeException(
                    'Insufficient funds. Minimum balance must be maintained.'
                );
            }

            $account->balance = $newBalance;
        }

        /*
         * Save the updated account balance.
         *
         * This will also trigger AccountObserver,
         * which handles the low-balance notification.
         */
        $account->save();
    }
}