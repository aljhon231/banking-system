<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('account')
            ->latest()
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::all();

        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'account_id' => ['required', 'exists:accounts,id'],
                'type' => ['required', 'in:deposit,withdrawal'],
                'amount' => ['required', 'numeric', 'min:1'],
                'reference_number' => ['nullable', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
            ]
        );

        $validator->after(function ($validator) use ($request) {

            if (!$request->account_id || !$request->amount) {
                return;
            }

            $account = Account::find($request->account_id);

            if (!$account) {
                return;
            }

            $amount = (float) $request->amount;

            if ($request->type === 'withdrawal') {

                $newBalance = (float) $account->balance - $amount;

                if ($newBalance < 0) {
                    $validator->errors()->add(
                        'amount',
                        'Insufficient funds. Balance cannot be negative.'
                    );
                } elseif ($newBalance < (float) $account->minimum_balance) {
                    $validator->errors()->add(
                        'amount',
                        'Insufficient funds. Minimum balance must be maintained.'
                    );
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {

            $account = Account::where('id', $request->account_id)
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (float) $request->amount;

            if ($request->type === 'withdrawal') {

                $newBalance = (float) $account->balance - $amount;

                if ($newBalance < 0) {
                    throw new \RuntimeException(
                        'Insufficient funds. Balance cannot be negative.'
                    );
                }

                if ($newBalance < (float) $account->minimum_balance) {
                    throw new \RuntimeException(
                        'Insufficient funds. Minimum balance must be maintained.'
                    );
                }
            }

            Transaction::create([
                'account_id' => $request->account_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'reference_number' => $request->reference_number
                    ?? 'TXN-' . strtoupper(uniqid()),
                'notes' => $request->notes,
            ]);
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction completed successfully.');
    }
}