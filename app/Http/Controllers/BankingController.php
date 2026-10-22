<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankingController extends Controller
{
    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|exists:accounts,account_number',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated) {

            $account = Account::where(
                'account_number',
                $validated['account_number']
            )->firstOrFail();

            $account->increment('balance', $validated['amount']);

            Transaction::create([
                'account_id' => $account->id,
                'type' => 'deposit',
                'amount' => $validated['amount'],
                'description' => 'Cash deposit',
            ]);
        });

        return back()->with(
            'success',
            'Deposit successful.'
        );
    }

    public function withdraw(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|exists:accounts,account_number',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated) {

            $account = Account::where(
                'account_number',
                $validated['account_number']
            )->lockForUpdate()->firstOrFail();

            $newBalance =
                $account->balance - $validated['amount'];

            if ($newBalance < $account->minimum_balance) {
                abort(
                    422,
                    'Insufficient balance. Minimum balance must be maintained.'
                );
            }

            $account->decrement('balance', $validated['amount']);

            Transaction::create([
                'account_id' => $account->id,
                'type' => 'withdrawal',
                'amount' => $validated['amount'],
                'description' => 'Cash withdrawal',
            ]);
        });

        return back()->with(
            'success',
            'Withdrawal successful.'
        );
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_account' => 'required|exists:accounts,account_number',
            'to_account' => 'required|exists:accounts,account_number|different:from_account',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated) {

            $sender = Account::where(
                'account_number',
                $validated['from_account']
            )->lockForUpdate()->firstOrFail();

            $receiver = Account::where(
                'account_number',
                $validated['to_account']
            )->lockForUpdate()->firstOrFail();

            $newBalance =
                $sender->balance - $validated['amount'];

            if ($newBalance < $sender->minimum_balance) {
                abort(
                    422,
                    'Insufficient balance for transfer.'
                );
            }

            $sender->decrement(
                'balance',
                $validated['amount']
            );

            $receiver->increment(
                'balance',
                $validated['amount']
            );

            Transaction::create([
                'account_id' => $sender->id,
                'related_account_id' => $receiver->id,
                'type' => 'transfer',
                'amount' => $validated['amount'],
                'description' =>
                    'Transfer to ' . $receiver->account_number,
            ]);
        });

        return back()->with(
            'success',
            'Transfer successful.'
        );
    }
}