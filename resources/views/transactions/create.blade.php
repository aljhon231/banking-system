<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Transaction</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #198754;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #146c43;
        }

        .error {
            color: #dc3545;
            margin-top: 5px;
        }

        .back {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <a class="back" href="{{ route('transactions.index') }}">
        ← Back to Transactions
    </a>

    <h1>Create Transaction</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">

        @csrf

        <label for="account_id">Account</label>

        <select name="account_id" id="account_id" required>

            <option value="">-- Select Account --</option>

            @foreach ($accounts as $account)

                <option
                    value="{{ $account->id }}"
                    {{ old('account_id') == $account->id ? 'selected' : '' }}
                >
                    {{ $account->account_number }}
                    - Balance: ₱{{ number_format($account->balance, 2) }}
                </option>

            @endforeach

        </select>

        <label for="type">Transaction Type</label>

        <select name="type" id="type" required>

            <option value="">-- Select Type --</option>

            <option value="deposit">
                Deposit
            </option>

            <option value="withdrawal">
                Withdrawal
            </option>

        </select>

        <label for="amount">Amount</label>

        <input
            type="number"
            name="amount"
            id="amount"
            step="0.01"
            min="1"
            value="{{ old('amount') }}"
            required
        >

        <label for="reference_number">
            Reference Number
        </label>

        <input
            type="text"
            name="reference_number"
            id="reference_number"
            value="{{ old('reference_number') }}"
            placeholder="Optional"
        >

        <label for="notes">Notes</label>

        <textarea
            name="notes"
            id="notes"
            placeholder="Optional notes"
        >{{ old('notes') }}</textarea>

        <button type="submit">
            Save Transaction
        </button>

    </form>

</div>

</body>

</html>