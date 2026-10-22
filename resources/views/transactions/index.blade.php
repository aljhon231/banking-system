<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Banking Transactions</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background: #198754;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .button:hover {
            background: #146c43;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        .deposit {
            color: #198754;
            font-weight: bold;
        }

        .withdrawal {
            color: #dc3545;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Banking Transactions</h1>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <a
        href="{{ route('transactions.create') }}"
        class="button"
    >
        + Create Transaction
    </a>

    @if ($transactions->count() > 0)

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Reference</th>
                    <th>Notes</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($transactions as $transaction)

                    <tr>

                        <td>
                            {{ $transaction->id }}
                        </td>

                        <td>
                            {{ $transaction->account->account_number }}
                        </td>

                        <td
                            class="{{ $transaction->type }}"
                        >
                            {{ ucfirst($transaction->type) }}
                        </td>

                        <td>
                            ₱{{ number_format($transaction->amount, 2) }}
                        </td>

                        <td>
                            {{ $transaction->reference_number ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $transaction->notes ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">
            No transactions found.
        </div>

    @endif

</div>

</body>

</html>